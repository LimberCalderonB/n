<?php
session_start(); // Aquí se inicia la sesión
include_once "cabecera.php";

//ALERTA DE REGISTRO

if(isset($_SESSION['registro_exitoso_rol']) && $_SESSION['registro_exitoso_rol'] == true){
    echo "<script>
    Swal.fire({
        position: 'top-end',
        icon: 'success',
        title: 'Registro Exitoso',
        showConfirmButton: false,
        timer: 1500
    });
    </script>";
    unset($_SESSION['registro_exitoso_rol']); 
}
?>
<!--------------------------------------------------------->

<section class="full-width header-well">
    <div class="full-width header-well-icon">
        <i class="zmdi zmdi-washing-machine"></i>
    </div>
    <div class="full-width header-well-text">
        <p class="text-condensedLight">
            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Unde aut nulla accusantium minus corporis accusamus fuga harum natus molestias necessitatibus.
        </p>
    </div>
</section>

<div class="mdl-tabs mdl-js-tabs mdl-js-ripple-effect">
    <div class="mdl-tabs__tab-bar">
        <a href="#tabNewProduct" class="mdl-tabs__tab is-active">NUEVO</a>
        <a href="#tabListrol" class="mdl-tabs__tab">LISTA DE ROLES</a>
    </div>
    <div class="mdl-tabs__panel is-active" id="tabNewProduct">
        <div class="mdl-grid">
            <div class="mdl-cell mdl-cell--12-col">
                <div class="full-width panel mdl-shadow--2dp">
                    <div class="full-width panel-tittle bg-primary text-center tittles">
                        Nuevo Rol
                    </div>
                    <div class="full-width panel-content">
                        <form action="../controlador_admin/ct_rol.php" method="post" class="row g-3 needs-validation" novalidate>
                            <div class="mdl-grid">
                                <div class="mdl-cell mdl-cell--12-col">
                                    <legend class="text-condensedLight"><i class="zmdi zmdi-border-color"></i> &nbsp; INFORMACION</legend><br>
                                </div>
                                <div class="mdl-cell mdl-cell--6-col mdl-cell--8-col-tablet">
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label <?php echo isset($_SESSION['error_rol']) ? 'is-invalid' : ''; ?>">
                                        <input class="mdl-textfield__input" type="text" id="nombre" name="nombre" value="<?php echo isset($_SESSION['nombre_rol']) ? $_SESSION['nombre_rol'] : ''; ?>">
                                        <label class="mdl-textfield__label" for="nombre">Rol</label>
                                        <?php if(isset($_SESSION['error_rol'])): ?>
                                            <span class="mdl-textfield__error" style="color:red;"><?php echo $_SESSION['mensaje_rol']; ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <p class="text-center">
                                <button class="mdl-button mdl-js-button mdl-button--fab mdl-js-ripple-effect mdl-button--colored bg-primary" id="agregar" type="submit">
                                    <i class="zmdi zmdi-plus"></i>
                                </button>
                                <div class="mdl-tooltip" for="agregar">Agregar Rol</div>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mdl-tabs__panel" id="tabListrol">
        <div class="mdl-grid">
            <div class="mdl-cell mdl-cell--4-col-phone mdl-cell--8-col-tablet mdl-cell--8-col-desktop mdl-cell--2-offset-desktop">
                <div class="full-width panel mdl-shadow--2dp">
                    <div class="full-width panel-tittle bg-success text-center tittles">
                        LISTA DE ROLES
                    </div>
                    <div class="full-width panel-content">
                        <form action="#">
                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--expandable">
                                <label class="mdl-button mdl-js-button mdl-button--icon" for="searchRoles">
                                    <i class="zmdi zmdi-search"></i>
                                </label>
                                <div class="mdl-textfield__expandable-holder">
                                    <input class="mdl-textfield__input" type="text" id="searchRoles">
                                    <label class="mdl-textfield__label"></label>
                                </div>
                            </div>
                        </form>
                        <div class="mdl-list">
                            <?php
                            include_once "../../conexion.php";

                            if ($conn->connect_error) {
                                die("Error de conexión: " . $conn->connect_error);
                            }

                            $sql = "SELECT idrol, nombre FROM rol";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                $count = 1; // Variable para enumerar los roles
                                while ($row = $result->fetch_assoc()) {
                                    ?>
                                    <div class='mdl-list__item mdl-list__item--two-line'>
                                        <span class='mdl-list__item-primary-content'>
                                            <i class='zmdi zmdi-account mdl-list__item-avatar'></i> <!-- Ícono de usuario -->
                                            <span><?php echo $count . ". " . $row['nombre']; ?></span> <!-- Mostrar el nombre del rol -->
                                            <span class='mdl-list__item-sub-title'>ID: <?php echo $row['idrol']; ?></span> <!-- Mostrar el ID del rol -->
                                        </span>
                                        <span class='mdl-list__item-secondary-action'>
                                            <?php if ($row['nombre'] !== 'Administrador') { ?> <!-- Mostrar solo si el rol no es de administrador -->
                                                <button class='mdl-button mdl-js-button mdl-button--icon' onclick='showDetails(<?php echo $row["idrol"]; ?>)'>
                                                    <i class='zmdi zmdi-eye'></i>
                                                </button>
                                                <!-- Agregar id al botón de eliminar -->
                                                <button id='deleteBtn_<?php echo $row["idrol"]; ?>' class='mdl-button mdl-js-button mdl-button--icon' onclick='confirmDelete(<?php echo $row["idrol"]; ?>)'>
                                                    <i class='zmdi zmdi-delete'></i>
                                                </button>
                                            <?php } ?>
                                        </span>
                                    </div>
                                    <li class='full-width divider-menu-h'></li> <!-- Línea divisoria -->
                                    <?php
                                    $count++; // Incrementar el contador de roles
                                }
                            } else {
                                echo "No se encontraron roles.";
                            }
                            $conn->close();
                            ?>
                        </div>
                        <script>
                            //----------------------------------------------------------------
                            //          Alerta de eliminacion de rol
                            //----------------------------------------------------------------
                            function confirmDelete(idrol) {
                                Swal.fire({
                                    title: '¿Estás seguro?',
                                    text: "¡No podrás revertir esto!",
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonColor: '#3085d6',
                                    cancelButtonColor: '#d33',
                                    confirmButtonText: 'Sí, eliminarlo!',
                                    cancelButtonText: 'Cancelar'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        // Realizar la solicitud AJAX para eliminar el rol
                                        $.ajax({
                                            type: 'POST',
                                            url: '../crud/rol/crud_rol_eliminar.php', 
                                            data: {idrol: idrol},
                                            success: function(response) {
                                                // Mensaje de eliminacion
                                                Swal.fire({
                                                    title: '¡Eliminado!',
                                                    text: 'El rol ha sido eliminado.',
                                                    icon: 'success'
                                                }).then((result) => {
                                                    // Recargar la página después de eliminar el rol
                                                    location.reload();
                                                });
                                            },
                                            error: function(xhr, status, error) {
                                                // Error de Eliminacion
                                                Swal.fire({
                                                    title: 'Error',
                                                    text: 'Se produjo un error al intentar eliminar el rol.',
                                                    icon: 'error'
                                                });
                                            }
                                        });
                                    }
                                });
                            }
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
// Limpiar las variables
unset($_SESSION['error_rol']); 
unset($_SESSION['mensaje_rol']);
unset($_SESSION['nombre_rol']);
include_once "pie.php";
?>