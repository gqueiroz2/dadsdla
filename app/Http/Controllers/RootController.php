<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use App\dataBase;
use App\sql;
use App\base;

class RootController extends Controller{

    public function test(){
        
        $sql = new sql();
        $db = new dataBase();
        $con = $db->openConnection("DLA");

        $ag = array(

                    "Abbott Laboratories De Colombia S.A.",
                    "Acdi Voca",
                    "Aerovias De Integracion Regional S.A. Aires S.A.",
                    "Aerovias Del Continente Americano S.A. Avianca",
                    "Agencia De Viajes Y Turismo Aviatur S.A.",
                    "Ajinomoto Do Brasil Industria E Comercio De Alimentos Ltda",
                    "Alcaldia De Medellin",
                    "Alimentos Carnicos S.A.S.",
                    "Alimentos Polar Colombia S.A.S",
                    "Almacenes Exito S.A.",
                    "Almacenes Maximo S.A.",
                    "Alpina Productos Alimenticios S.A.",
                    "Altipal S.A.",
                    "Apple Colombia  S.A.S.",
                    "Arquitectura Y Concreto Sas",
                    "Aruba Tourism Authority Ata Sucursal Colombia",
                    "Automotores Comerciales Autocom S.A.",
                    "Automotriz Escandinava S A S",
                    "Autopistas Del Café Sa",
                    "Avon Colombia Sas",
                    "Babymarket S.A.S",
                    "Banco Bbva Colombia",
                    "Banco Colpatria",
                    "Banco Colpatria Multibanca Colpatria S.A.",
                    "Banco Davivienda S.A.",
                    "Banco De Occidente S.A.",
                    "Banco Mundo Mujer S.A. O Mundo Mujer El Banco De La Comunidad O Mundo Mujer",
                    "Banco Popular S.A.",
                    "Bancolombia S.A.",
                    "Bavaria S.A.",
                    "Bcsc S.A.",
                    "Beiersdorf S.A.",
                    "Bigfoot Colombia S.A.S",
                    "Bimbo De Colombia S.A.",
                    "Bmc Bolsa Mercantil De Colombia S.A.",
                    "Bogota Distrito Capital",
                    "Boston Medical Groupe De Colombia S.A.S.",
                    "Caja Colombiana De Subsidio Familiar Colsubsidio",
                    "Caja De Compensacion Familiar Cafam",
                    "Caja De Compensacion Familiar De Antioquia Confama",
                    "Camara De Comercio De Bogota",
                    "Casa Editorial El Tiempo S.A.",
                    "Casa Luker S.A.",
                    "Celsia Sa Esp",
                    "Cencosud Colombia S.A.",
                    "Central Cervecera De Colombia S.A.S",
                    "Centro Comercial Y Entretenimiento Atlantis Plaza Propiedad Horizontal",
                    "Citibank Colombia S.A.",
                    "Ciudad Lunar Producciones Ltda",
                    "Coca Cola Industrias De Responsabilidad Ltda",
                    "Coca Cola Servicios De Colombia S.A.",
                    "Codensa S.A. Esp",
                    "Colgate Palmolive Compañía",
                    "Colmedica Medicina Prepagada S.A.",
                    "Colombia Movil S.A. E.S.P.",
                    "Colombia Telecomunicaciones",
                    "Colombiana De Comercio S.A.",
                    "Colombina S.A.",
                    "Colombo Española De Conservas Ltda",
                    "Compañia Colombiana De Ceramica S.A.S",
                    "Compania De Financiamiento Tuya S.A.",
                    "Compañía De Galletas Noel S.A.S.",
                    "Compañia De Medicina Prepagada Colsanitas S.A.",
                    "Compañia De Seguros De Vida Colmena S.A",
                    "Compañia Global De Pinturas S.A.",
                    "Compañía Nacional De Chocolates S.A.S.",
                    "Compañía Nacional De Levaduras Levapan S.A.",
                    "Compania Panamena De Aviacion S.A.",
                    "Comunicacion Celular S.A.",
                    "Consorcio Ses Puente Magdalena",
                    "Continente S.A. (Colombia)",
                    "Cooperativa Colanta Ltda",
                    "Cooperativa Multiactiva De Servicios Solidarios Copservir Ltda",
                    "Coordinadora Mercantil S.A.",
                    "Corferias Inversiones S.A.S",
                    "Corporacion Colombiana De Padres Y Madres",
                    "Corporacion De Ferias Y Exposiciones Sa Usuario",
                    "Corporacion Maloka De Ciencia, Tecnologia E Innovacion",
                    "Corporación Para El Desarrollo Del Departemento Del Meta",
                    "Corporacion Portafolio Verde",
                    "Corporacion Universidad Del Sinu Elias Bechara Zainum",
                    "Cruceros Turismo De Colombia S.A.S.",
                    "Detergentes Ltda",
                    "Diageo Colombia S.A.",
                    "Dicorp S.A.",
                    "Diez Medellin S.A.S.",
                    "Discovery Channel Latin Ameica",
                    "Distribuidora Andina De Combustibles S.A.",
                    "Distrito Especial Industrial Y Portuario De Barranquilla",
                    "Ecopetrol S.A.",
                    "Editora Urbana  Ltda",
                    "Education Hub S.A.S.",
                    "Embajada De Los Estados Unidos",
                    "Empresas Publicas De Medellin",
                    "Entidad Promotora De Salud Famisanar Ltda Cafam Colsubsidio",
                    "Entidad Promotora De Salud Famisanar.S.A.S",
                    "Escuela Colombiana  De Ingieneria Julio Garavito",
                    "Estudios Y Proyectos Del Sol Sas",
                    "Eterna S.A.",
                    "Fabrica De Especias Y Pr",
                    "Falabella De Colombia Sa",
                    "Federacion Nacional De Cafeteros De Colombia",
                    "Federacion Nacional De Molineros De Trigo Fedemol",
                    "Femclinic S.A.S",
                    "Femclinic S.A.S.",
                    "Ferrero Latin America  Developing Market's",
                    "Fiducoldex Proexport Colombia",
                    "Financiera Comultrasan",
                    "Fondo Adaptación",
                    "Fondo De Garantias De Instituciones Financieras",
                    "Fondo De Tecnologias De La Información Y Las Comunicaciones",
                    "Fondo Nacional Del Ahorro",
                    "Ford Motor Colombia S.A.S.",
                    "Frisby S.A.",
                    "Fundacion Panamericana Para El Desarrollo Colombia Fupad",
                    "Fundacion Plan",
                    "Fundacion Universitaria Autonoma De Las Americas",
                    "Fundacion Universitaria Del Area Andina",
                    "Fundacion Universitaria Konrad Lorenz",
                    "Gaseosas De Cordoba S.A.S",
                    "Gaseosas Lux S.A.",
                    "Gaseosas Posada Tobon S.A",
                    "General Motors Colmotores",
                    "Genfar S.A.",
                    "Gestion Cargo Zona Franca Sas",
                    "Groupe Seb De Colombia",
                    "Grupo Aval Acciones Y Valores S.A.",
                    "Grupo Empresarial Richnestt S.A.S.",
                    "Grupo Energia Bogota Sa Esp",
                    "H&M Hennes & Mauritz Colombia S.A.S",
                    "Hartung Y CIA S.A.",
                    "Heel Colombia Ltda",
                    "Huawei Device (Hong Kong) Co., Limited",
                    "Huawei Techonologies Colombia Sas",
                    "Hughes De Colombia Sas",
                    "I R C C S.A.S Industria De Restaurantes Casuales S.A.S",
                    "Iberia Lineas Aereas De España S.A. Operadora Sucursal Colombiana",
                    "Igt Juegos S.A.S",
                    "Ilumno Servicios De Colombia S.A.S",
                    "Industria Colombiana De Café S.A.S",
                    "Industrias Bicicletas Milan S.A.",
                    "Ingenio Del Cauca S.A.",
                    "Ingenio Providencia S.A",
                    "Instituto Distrital De Proteccion Y Bienestar Animal - Idpyba",
                    "Inversiones Crm S.A.S.",
                    "Johnson & Johnson Colombia",
                    "Khiron Colombia S.A.S.",
                    "Laboratorios Funat S.A.S",
                    "Laboratorios Siegfried S.A.S.",
                    "Leader Search S.A.",
                    "Lenovo Asia Pacific Limited Sucursal Colombia",
                    "Lg Electronics Colombia Ltda",
                    "Loreal Colombia S.A.",
                    "Matrix Grupo Empresarial S.A.S",
                    "Meals Mercadeo De Alimentos De Colombia S.A.S.",
                    "Medicos Sin Fronteras España",
                    "Medplus Medicina Prepagada S.A.",
                    "Merck S.A.",
                    "Merqueo S.A.S.",
                    "Motores Y Maquinas S.A. Motorysa",
                    "Nestle De Colombia S.A.",
                    "Nestle Purina Pet Care De Colombia S.A",
                    "Novafem S.A.S",
                    "Nuevas Bebidas De Colombia Ltda",
                    "Odinsa S.A.",
                    "OMD Colombia S.A.S",
                    "Orf S.A",
                    "Organizacion Terpel S.A",
                    "Patrimonio Autonomo Fondo Nacional Del Turismo Fontur",
                    "Pepsico Alimentos Colombia Ltda",
                    "Pinturas Tito Pabon En C",
                    "Politecnico Grancolombiano",
                    "Porvenir",
                    "Procter & Gamble Colombia Ltda",
                    "Productora De Alimentos Concentrados Para Animales Contegral S.A.S.",
                    "Productos Alimenticios Doria S.A.S.",
                    "Productos Familia S.A.",
                    "Productos Naturales De La Sabana S.A. La Alqueria",
                    "Productos Ramo S.A.",
                    "Proteccion S.A.",
                    "Puntos Colombia S.A.S",
                    "Rappi S.A.S",
                    "Recamier S.A.",
                    "Renault Sociedad De Fabricacion De Automotores Sas",
                    "Samsung Electronics Colombia S.A.",
                    "Sanofi-Aventis De Colombia S.A.",
                    "Scotiabank Colpatria",
                    "Seguros Bolivar",
                    "Servicio Aereo  A Territorios Nacionales S.A.",
                    "Servincluidos Ltda",
                    "Sociedad Concesionaria Operadora",
                    "Sodimac Colombia S.A. (Grupo Chileno)",
                    "Sonovista Publicidad S.A",
                    "Sony Colombia S.A.",
                    "Supertiendas Y Droguerias Olimpica S.A.",
                    "Tct Mobile Sa De Cv",
                    "Tecnoquimicas S.A.",
                    "Tecnosur",
                    "Telefonica Celular De Nicaragua",
                    "Telefonica De Costa Rica (Colombia)",
                    "Telefonica Moviles El Salvador,s.A. De C.V.",
                    "Telefonica Moviles Guatemala Sociedad Anonima",
                    "Telefonicas Moviles De Panama, S.A.",
                    "Televisión Regional Del Oriente Limitada Canal Tro",
                    "Telmex Colombia S.A.",
                    "Torre Cafe Aguila Roja & CIA S.A.",
                    "Transitions Optical Inc",
                    "Transportadora Comercial Colombiana S.A. Tcc ( Do Not Use)",
                    "Transportadora Comercial Colombiana Tcc",
                    "Trivago Spain Sl.",
                    "Une Epm Telecomunicaciones S.A.",
                    "Unilever Andina Colombia S.A.",
                    "Union De Droguistas S.A. Unidrogas S.A",
                    "United International Pictures Colombia Ltda",
                    "Universidad Autonoma De Occidente",
                    "Universidad Catolica Luis Amigo",
                    "Universidad Cooperativa De Colombia"






        );

        $count = 0;
        for ($a=0; $a < sizeof($ag); $a++) { 


            $select[$a] = "SELECT * FROM client_unit WHERE ( name LIKE '%".$ag[$a]."%')";

            //var_dump($select[$a]);

            $res = $con->query($select[$a]);

            $from = array("name");


            $novos[$count]['name'] = $ag[$a];
            if( $sql->fetch($res,$from,$from) ){
                $novos[$count]['status'] = true;                
            }else{
                $novos[$count]['status'] = false;                
            }
            $count++;


        }

        $cc = 0;
        for ($n=0; $n < sizeof($novos); $n++) { 
            if(!$novos[$n]['status']){
                $new[$cc] = $novos[$n]['name'];
                $cc++;
            }
        }


        var_dump(sizeof($novos));
        if($new){
            var_dump(sizeof($new));
        }

        //var_dump($new);

        //var_dump($novos);

        $values = "";
        
        for ($m=0; $m < sizeof($new); $m++) { 
            $insert[$m] = "INSERT INTO client_unit 
                                    (client_id,origin_id,name)
                            VALUES('5975','1', \"".  addslashes($new[$m])."\" )";
/*
            if( $con->query( $insert[$m] )){
                var_dump("FOI");
            }else{
                var_dump("ERRO");
            }
  */

        }

        var_dump($insert);
       


    }

    public function dataCurrentThrough(){

    	$db = new dataBase();
        $base = new base();
    	$con = $db->openConnection("DLA");

        $current = array("IBMS","CMAPS","Digital");
    	$tables = array("ytd","cmaps","digital");

    	for ($t=0; $t < sizeof($tables); $t++) { 
    		$status[$t] = "SHOW TABLE STATUS FROM DLA LIKE '".$tables[$t]."'";
    		$res[$t] = $con->query($status[$t]);

    		if($res[$t] && $res[$t]->num_rows > 0){
    			$row = $res[$t]->fetch_assoc();
    			//if(isset($row['Update_time'])){
                    $updateTime[$t] = $row['Update_time'];
                //}else{
                  //  $updateTime[$t] = '2019-07-02 18:09:35';
                //}
    		}else{
    			$updateTime = false;
    		}

    	}

    	return view("dataCurrentThrough",compact("updateTime",'current','base'));
    }

}
