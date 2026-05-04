<?php

namespace Core;

class Pages {
    public function render(string $pageName, array $params = null): void {
        if($params !== null){
            foreach($params as $name => $value){
                $$name = $value;
            } 
        }

        require_once BASE_PATH . "/src/views/layout/header.php";
        require_once BASE_PATH . "/src/views/$pageName.php";
        require_once BASE_PATH . "/src/views/layout/footer.php";
    }
}