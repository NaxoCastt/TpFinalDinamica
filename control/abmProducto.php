<?php

    class abmProducto {

        public function altaProducto($datos){
            $objProducto = new Producto();
            $objProducto->cargar($datos['idproducto'], $datos['pronombre'], $datos['prodetalle'], $datos['procantstock']);
            return $objProducto->insertar();
        }

        //borrado logico 
        public function bajaProducto($idproducto){
            $objProducto = new Producto();
            $objProducto->buscar($idproducto);
            $objProducto->setProcantstock(-1);
            return $objProducto->modificar();
        }

        public function modificacionProducto($datos){
            $objProducto = new Producto();
            $objProducto->buscar($datos['idproducto']);
            $objProducto->cargar($datos['idproducto'], $datos['pronombre'], $datos['prodetalle'], $datos['procantstock']);
            return $objProducto->modificar();
        }

        public function buscarProducto($idproducto){
            $objProducto = new Producto();
            $colProductos = $objProducto->listar("idproducto = $idproducto");
            return $colProductos;
        }

        public function listarProductos($where = "procantstock >= 0"){
            $objProducto = new Producto();
            $colProductos = $objProducto->listar($where);
            return $colProductos;
        }
    }