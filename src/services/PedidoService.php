<?php
namespace Services;

use Repositories\PedidoRepositorio;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dompdf\Dompdf;
use Dompdf\Options;

class PedidoService {
    private PedidoRepositorio $repository;

    public function __construct() {
        $this->repository = new PedidoRepositorio();
    }

    public function procesarCompraCompleta(array $datosPedido, array $itemsCarrito): bool {
        $this->repository->empezarTransaccion();

        $pedidoId = $this->repository->guardarPedido($datosPedido);

        if ($pedidoId > 0) {
            foreach ($itemsCarrito as $item) {
                $producto = $item['producto'];
                $cantidadPedida = $item['cantidad'];

                if ($producto->getStock() < $cantidadPedida) {
                    $this->repository->rollback();
                    return false; 
                }

                $this->repository->guardarLinea(
                    $pedidoId,
                    $producto->getId(),
                    $cantidadPedida,
                    $producto->getPrecio(),
                    $item['subtotal']
                );

                $this->repository->actualizarStock($producto->getId(), $cantidadPedida);
            }

            $this->repository->commit();

            // Enviamos el correo con todos los detalles 
            $this->enviarEmailConfirmacion($pedidoId, $datosPedido, $itemsCarrito);

            return true;
        }

        $this->repository->rollback();
        return false;
    }

    private function enviarEmailConfirmacion(int $pedidoId, array $datos, array $items) {
        $mail = new PHPMailer(true);
        try {
            // Configuramos SMTP
            $mail->isSMTP();
            $mail->Host       = $_ENV['SMTP_HOST'];
            $mail->SMTPAuth   = true;
            $mail->Username   = $_ENV['SMTP_USER'];
            $mail->Password   = $_ENV['SMTP_PASS']; 
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = $_ENV['SMTP_PORT'];

            $mail->setFrom($_ENV['SMTP_USER'], 'Tienda Informatica');
            $mail->addAddress($_SESSION['email'], $_SESSION['nombre']);  

            // Generamos el HTML para el Pdf
            $fecha = date("d/m/Y");
            $htmlPdf = "<h1>Factura Pedido Nº $pedidoId</h1>";
            $htmlPdf .= "<p><strong>Fecha:</strong> $fecha</p>";
            $htmlPdf .= "<p><strong>Cliente:</strong> " . $_SESSION['nombre'] . "</p>";
            
            // Dirección
            $htmlPdf .= "<p><strong>Dirección de envío:</strong><br>";
            $htmlPdf .= $datos['direccion'] . "<br>";
            $htmlPdf .= $datos['localidad'] . " (" . $datos['provincia'] . ")</p>";
            
            $htmlPdf .= "<table border='1' width='100%' style='border-collapse: collapse;'>
                            <thead>
                                <tr style='background-color: #eee;'>
                                    <th>Producto</th>
                                    <th>Cantidad</th>
                                    <th>Precio</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>";
            foreach ($items as $item) {
                $htmlPdf .= "<tr>
                                <td>{$item['producto']->getNombre()}</td>
                                <td>{$item['cantidad']}</td>
                                <td>{$item['producto']->getPrecio()}€</td>
                                <td>{$item['subtotal']}€</td>
                            </tr>";
            }
            $htmlPdf .= "</tbody></table>";
            $htmlPdf .= "<h3>Total Pagado: {$datos['coste_total']}€</h3>";

            // Creamos el Pdf
            $options = new \Dompdf\Options();
            $options->set('isHtml5ParserEnabled', true);
            $dompdf = new \Dompdf\Dompdf($options);
            $dompdf->loadHtml($htmlPdf);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            
            $pdfOutput = $dompdf->output(); 

            // Adjuntamos el Pdf
            $mail->addStringAttachment($pdfOutput, "Factura_Pedido_$pedidoId.pdf");

            // Cuerpo del email
            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->Subject = "Tu factura - Pedido Nº $pedidoId";
            $mail->Body    = "Hola " . $_SESSION['nombre'] . ",<br><br>
                            Gracias por tu compra. Te adjuntamos la factura de tu pedido <b>Nº $pedidoId</b> en formato PDF.<br><br>
                            Saludos,<br>Tienda Informática.";

            $mail->send();

        } catch (Exception $e) {
            // Error silencioso
        }
    }

    public function buscarPorUsuario($usuarioId) {
    
        return $this->repository->buscarPorUsuario($usuarioId);
    }
}