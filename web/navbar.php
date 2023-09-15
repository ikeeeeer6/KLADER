<?php 
require_once ('../MysqliDb.php'); 
require_once ('../conexio.php');
session_start();
?>

<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="../style/navbar.css">
  <title>KLÄDER</title>
  <!-- Bootstrap -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
  <div class="container-fluid">
    <div class="bg-light">
		<nav class="navbar navbar-expand-md navbar-light bg-light border-3 border-bottom border-primary">
    <div class="container-fluid">
        <a href="./index.php"><img src="../imatges/iconos/looggo.jpg" style="width: 200px; height: auto;" width="30" height="24" class="d-inline-block align-top"></a>               
        <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#MenuNavegacion">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>

    <!-- buscador -->
    <div id="MenuNavegacion" class="collapse navbar-collapse">
      <ul class="navbar-nav ms-3">
        <li class="nav-item">
          <div class="buscador_total">
            <div style="width: 75%; height: 30px; float: left;">
              <input type="search" placeholder="Buscar..." id="buscador"/>
            </div >
            <div style="width: 25%; height: 30px; float: left; padding-left: 10px; cursor: pointer;">
              <img onclick="Buscador()" src="https://cdn0.iconfinder.com/data/icons/slim-square-icons-basics/100/basics-19-32.png">
            </div>
          </div>
        </li>
        <!-- menu home -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle medio" href="#" role="button" data-bs-toggle="dropdown">
            Home
          </a>
          <ul class="dropdown-menu">
            <?php
                //totes les categories de home           
                $categories = $db->get("categoria");
                foreach($categories as $categoria){
                    ?>
                        <li><a class="dropdown-item" href="buscador.php?b=<?php echo $categoria['categoria_nom'] . "_1_3" ?>"><?php echo $categoria['categoria_nom'] ?></a></li>
                    <?php
                }
            ?>
          </ul>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle medio" href="#" role="button" data-bs-toggle="dropdown">
            Dona
          </a>
          <ul class="dropdown-menu">
          <?php
                //totes les categories de dona                        
                $categories = $db->get("categoria");
                foreach($categories as $categoria){
                    ?>
                        <li><a class="dropdown-item" href="buscador.php?b=<?php echo $categoria['categoria_nom'] . "_2_3" ?>"><?php echo $categoria['categoria_nom'] ?></a></li>
                    <?php
                }
            ?>
           </ul>
        </li>
      </ul>
      <!-- depenen del sesion mostro un o altre -->
      <ul class="navbar-nav ms-3">
        <li class="nav-item">
            <?php if ( $_SESSION['usuari_id'] == 1 ){ ?>
                <a class="nav-link text-nowrap medio" href="../web/admin.php">Admin</a>
            <?php } elseif ( $_SESSION['usuari_id'] != 1 && $_SESSION['usuari_id'] != "" ) { ?>
                <a class="nav-link text-nowrap medio" href="../web/perfil.php">Perfil</a>
            <?php } elseif( $_SESSION['usuari_id'] == "") {?>
                <a class="nav-link text-nowrap medio" href="../web/login.php">Iniciar Sessió</a>
            <?php }?>
        </li>
      </ul>
      <?php
      //depenen del sesion mostro un o altre
      if($_SESSION['usuari_id'] != 1 && $_SESSION['usuari_id'] != ""){
      ?>
      <ul class="navbar-nav ms-3">
        <li class="nav-item">
          <a class="nav-link text-nowrap medio" href="../web/cistella.php">Cistella</a>
        </li>
      </ul>
      <?php
      }
      ?>
      <?php 
      if($_SESSION['usuari_id'] != ""){
      ?>
      <ul class="navbar-nav ms-3">
        <li class="nav-item">
          <a class="nav-link text-nowrap medio" style="cursor: pointer;" onclick="DestroySession('<?php echo $_SESSION['usuari_id'];?>')">Sortir</a>
        </li>
      </ul>
      <?php
      }
      ?>
    </div>
  </nav>
</div> 
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>

</body>
</html>
<script>
  function DestroySession(id){
    
    //detroy session
    data = {"id":id};
        $.ajax({
            type: 'POST', 
            data: data,
            url: "funcions/destroy-session.php",
            success: function(valores)
            {
              var url = "https://daw.institutmontilivi.cat/klader/web/login.php";
              window.location.replace(url);
            }
        })
  }

  function Buscador(){
    
    var paraula = document.getElementById("buscador").value;

    //buscador
    var url = "https://daw.institutmontilivi.cat/klader/web/buscador.php?b="+paraula;
    window.location.replace(url);

  }


</script>
