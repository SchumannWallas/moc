# Reflexión — Actividad 2.2 (autoloading, namespaces y excepciones)

**Nombre:** Wallas SChumann Dos Santos

## 1. Namespaces: ¿qué problema resuelven? Pon un ejemplo de colisión.

Un namespace es un “apellido” para las clases, y resuelve el problema de que dos clases se llamen igual en el mismo sitio. Si otra persona del equipo creara su propia clase Libro, PHP no sabría cuál cargar. En mi proyecto la mía es App\Classes\Libro, y la otra podría ser, por ejemplo, Tienda\Libro, y convivirían sin problema. Un ejemplo real me pasó en db.php: dentro de namespace App\Config; escribí new PDO(...) sin use PDO;, y PHP buscó App\Config\PDO, que no existe, y dio Class "App\Config\PDO" not found. Los namespaces afectan a todos los nombres, así que hay que importar PDO con use o escribir \PDO.

## 2. PSR-4: explica cómo Composer sabe que `App\Classes\Libro` está en `app/Classes/Libro.php`.

En composer.json declaré "psr-4": { "App\\": "app/" }, que significa “todo lo que empiece por App\ está en la carpeta app/”. Composer quita ese prefijo, convierte las barras invertidas del resto en barras normales y añade .php. Con App\Classes\Libro queda app/ + Classes/Libro + .php, es decir, app/Classes/Libro.php. Esa regla se guarda en vendor/composer/autoload_psr4.php.

## 3. Autoloading: ¿qué diferencia hay entre `require_once` manual y el cargador de Composer?

Antes cada archivo empezaba con una lista de require_once de las clases que usaba, y si añadía una clase nueva tenía que acordarme de incluirla en cada sitio. Ahora cada página solo incluye vendor/autoload.php, que registra un cargador automático. Cuando PHP encuentra new MaterialRepo() y no conoce la clase, le pregunta a ese cargador, que busca el archivo y lo incluye en ese momento. Así solo se carga lo que se usa y no hay que mantener listas a mano. En mi proyecto las funciones (db(), requireLogin()) no se pueden cargar así, porque PSR-4 solo trabaja con clases. Por eso las puse en "files" de composer.json, y Composer las carga en cada petición.

## 4. Excepciones: ¿qué ventaja tiene lanzar `MaterialNoEncontradoException` en lugar de devolver `null`?

En MaterialRepo::findById(), si no existe el material lanzo la excepción en vez de devolver null. Con null, cada sitio que llamaba al método tenía que acordarse de comprobarlo, y si se le olvidaba salía un error confuso más adelante. Me pasó en material.php, donde mi if (!$material) estaba mal colocado. Con la excepción no se puede ignorar el error por descuido, y además lleva un mensaje (No existe un material con id 9999) y un tipo que identifica qué pasó. Por eso el método puede declarar que siempre devuelve MaterialBiblioteca, sin el ?.

## 5. Try/catch: ¿dónde capturas las excepciones y por qué ahí? ¿Qué pasa si no las capturas?

Las capturo en los controladores de public/: material.php captura MaterialNoEncontradoException y responde un 404 amigable, prestar.php captura las dos excepciones (404 y 400) y devolver.php captura la de “no encontrado”. Si no capturo una excepción, PHP muestra un “Fatal error: Uncaught...”.

## 6. `Throwable` vs `Exception`: ¿cuándo usarías cada uno?

No lo tengo claro todavía.

## 7. `finally`: pon un ejemplo de tu código donde se ejecute `finally`. ¿Por qué es útil?

Mi ejemplo está en PrestamoService::prestar(). Empiezo con $resultado = 'fallido' y solo lo cambio a 'ok' si el préstamo termina bien. El bloque finally hace un error_log con el material, el usuario y el resultado. Se ejecuta en los tres casos: si el préstamo funciona (ok), si el material ya estaba prestado (fallido, y la excepción sigue subiendo a prestar.php) y si el material no existe. Es útil porque garantiza que siempre quede registrado el intento sin tener que repetir el error_log en cada rama, y finally no se traga la excepción, solo hace algo antes de que continúe.

## 8. PSR-12: cita tres reglas que hayas aplicado y explica por qué mejoran la legibilidad.

No las apliqué.

## 9. `strict_types`: ¿qué cambia respecto al comportamiento por defecto? ¿Por qué es una buena práctica?

Por defecto PHP convierte tipos en silencio. Si llamo a new Libro('Título', 'Autor', 2020, 12345), el entero 12345 se convierte en el texto "12345" para el parámetro string $isbn y nadie se entera. Con declare(strict_types=1) esa llamada lanza un TypeError. Es buena práctica porque un fallo ruidoso al principio es más fácil de depurar que un dato que se deforma sin avisar. La declaración es por archivo, afecta a las llamadas que se hacen desde ese archivo, y permite adoptarla poco a poco. No la puse en header.php ni footer.php: son plantillas con HTML y la declaración tiene que ser la primera instrucción del archivo.

## Ampliaciones resueltas

Marcadas: ninguna

A4, autoload de funciones, A6, Git y vendor.

## Autoevaluación

Sinceramente, me cuesta mucho aún, tuve que pedir mucha ayuda a la IA, me quedé confuso al empezar usar los namespaces, me cuesta identificar cuando usar cada uno (require, use ( use dentro de la clase y use fuera de la clase)). Las excepciones debería estudiarlas mejor aún, porque no me enteré muy bien de como funciona la "herencia" en los distintos ficheros de excepciones que se crearon. Y por ultimo estoy intentado comprender la jerarquía de todos los ficheros.
