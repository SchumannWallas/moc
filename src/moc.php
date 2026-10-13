<?php
// Inicio entrada de datos.
$libro = [
    "titulo" => "El Quijote",
    "autor" => "Miguel de Cervantes",
    "anio" => 1605,
    "disponible" => false
];

$libros = [
    ["titulo" => "El Quijote", "autor" => "Miguel de Cervantes", "anio" => 1605, "disponible" => true],
    ["titulo" => "Dune", "autor" => "Frank Herbert", "anio" => 1965, "disponible" => false],
    ["titulo" => "Sapiens", "autor" => "Yuval Noah Harari", "anio" => 2011, "disponible" => true]
];

$libros2 = [
    ["titulo" => "El Quijote", "autor" => "Miguel de Cervantes", "genero" => "Novela", "anio" => 1605, "disponible" => true],
    ["titulo" => "Dune", "autor" => "Frank Herbert", "genero" => "Ciencia ficción", "anio" => 1965, "disponible" => false],
    ["titulo" => "Sapiens", "autor" => "Yuval Noah Harari", "genero" => "Ensayo", "anio" => 2011, "disponible" => true]
];

$libros3 = [
    ["id" => 1, "titulo" => "El Quijote", "autor" => "Miguel de Cervantes", "genero" => "Novela", "anio" => 1605, "disponible" => true],
    ["id" => 2, "titulo" => "Dune", "autor" => "Frank Herbert", "genero" => "Ciencia ficción", "anio" => 1965, "disponible" => false],
    ["id" => 3, "titulo" => "Sapiens", "autor" => "Yuval Noah Harari", "genero" => "Ensayo", "anio" => 2011, "disponible" => true]
];

$usuarios = [
    ["nombre" => "ana",   "clave" => "ana123",   "rol" => "usuario"],
    ["nombre" => "admin", "clave" => "admin123", "rol" => "admin"]
];

$usuarioIntentado = "ana";
$claveIntentada = "ana123";
$accion = "prestar";
$idLibro = 1;

// Fin entrada de datos.


// Primer ejercicio
echo "<h2>" . $libro["titulo"] . "</h2>";
echo "<p>" . $libro["autor"] . "</p>";
echo "<p>" . $libro["anio"] . "</p>";
echo "<p>" . ($libro["disponible"] ? "✅ Disponible" : "❌ No Disponible") . "</p>";

// Segundo ejercicio
function mostrarTodos($libros)
{
    foreach ($libros as $lib) {
        echo "<h2>" . $lib["titulo"] . "</h2>";
        echo "<p>" . $lib["autor"] . "</p>";
        echo "<p>" . $lib["anio"] . "</p>";
        echo "<p>" . ($lib["disponible"] ? "✅ Disponible" : "❌ No Disponible") . "</p>";
    }
    echo count($libros);
}
echo "<h1> Mi Biblioteca </h1>";
mostrarTodos($libros);

// Tercer ejercicio
function filtrarPorGenero($libros, $genero)
{
    $resultado = [];
    foreach ($libros as $libro) {
        if ($libro["genero"] == $genero) {
            $resultado[] = $libro;
        }
    }
    return $resultado;
}

echo "<h2>Todos los libros</h2>";
mostrarTodos($libros2);

echo "<h2>Novelas</h2>";
$novela = filtrarPorGenero($libros2, "Novela");
mostrarTodos($novela);

echo "<h2>Ciencia ficción</h2>";
$cienciaF = filtrarPorGenero($libros2, "Ciencia ficción");
mostrarTodos($cienciaF);


// Cuarto ejercicio
echo "<h1>Ejercicio 4 - Préstamo de libros</h1>";
function prestarLibro($libros, $id)
{
    foreach ($libros as $indice => $libro) {
        if ($libro["id"] == $id) {
            if ($libro["disponible"]) {
                $libros[$indice]["disponible"] = false;
                return $libros;
            } else {
                return null;
            }
        }
    }
    return null;
}


switch ($accion) {
    case "listar":
        mostrarTodos($libros3);
        break;
    case "prestar":
        $resultado = prestarLibro($libros3, $idLibro);

        if ($resultado !== null) {
            $libros3 = $resultado;
            echo "✅ Libro prestado correctamente";
            
        } else {
            echo "❌ No se puede prestar el libro";
        }
        break;
    case "prestados":
        foreach ($libros3 as $libro) {
            if (!$libro["disponible"]) {
                echo "<h2>" . $libro["titulo"] . "</h2>";
                echo "<p>" . $libro["autor"] . "</p>";
                echo "<p>" . $libro["anio"] . "</p>";
            }
        }
        break;
    default:
        echo "Acción no reconocida";
        break;
}

// Quinto Ejercicio
function login($usuarios, $nombre, $clave)
{
    foreach ($usuarios as $usuario) {
        if ($usuario["nombre"] == $nombre && $usuario["clave"] == $clave)
            return $usuario;
    }
    return null;
}

$userLogged = login($usuarios, $usuarioIntentado, $claveIntentada);
if ($userLogged !== null) {
    echo "<h2>Bienvenido " . $userLogged["nombre"] . "</h2>";
    if ($userLogged["rol"] === "admin") {
        echo "<h2>🔧 Tienes permisos de administrador.</h2>";
    }
    mostrarTodos($libros3);
} else {
    echo "❌ Usuario o contraseña incorrectos";
}

?>