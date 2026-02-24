<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Mi Proyecto Laravel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
        crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <script src="{{ asset('js/llamadas.js') }}" defer></script>

</head>

<body>

    <div class="container">
        <div class="row">
            <div class="col-12 my-4">
                <div id="alert-message" class="alert alert-danger d-none" role="alert">
                    <span id="error-message"></span>
                </div>
            </div>
        </div>
        <div class="row my-4">
            <div class="col-3 my-auto">
                <h1 class="">Productos</h1>
                <ul id="categories-list" class="list-group"></ul>
            </div>
            <div class="col-3 my-auto">
                <div class="align-text-bottom form-check form-switch">
                    <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked" checked>
                    <label class="form-check-label" for="flexSwitchCheckChecked">Activos</label>
                </div>
            </div>
            <div class="col-3 my-auto">
                <select class="form-select" aria-label="Categorías" id="category-filter">
                    <option value="">Todas las categorías</option>

                </select>
            </div>
            <div class="col-3 my-auto">
                <button id="btn-new-product" type="button" class="btn btn-primary">
                    Nuevo producto
                </button>
                <button id="btn-new-category" type="button" class="btn btn-primary">
                    Nueva categoría
                </button>
            </div>

            <div class="col-12">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">Nombre</th>
                            <th scope="col">Precio</th>
                            <th scope="col">Stock</th>
                            <th scope="col">Categoría</th>
                            <th scope="col">Estado</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="products-body"></tbody>
                </table>
            </div>
            <div class="col-12" id="paginate-links">
                <button id="load-products-prev" class="btn btn-primary d-none">Cargar Productos Anteriores</button>
                <button id="load-products-next" class="btn btn-primary d-none">Cargar Productos Siguientes</button>
            </div>

        </div>



        <div class="modal fade" id="newProduct" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="nuevoProductoLabel">Nuevo Producto</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <form id="new-product-form">
                            <div class="mb-3">
                                <div id="alert-message-name-product" class="alert alert-danger d-none" role="alert">
                                    <span id="error-message-name-product"></span>
                                </div>
                                <label for="nameProduct" class="form-label">Nombre del producto</label>
                                <input type="text" class="form-control" id="product-name"
                                    aria-describedby="productNameHelp">
                                <div id="productNameHelp" class="form-text">Máximo 255 caracteres.
                                </div>
                            </div>
                            <div class="mb-3">
                                <div id="alert-message-price-product" class="alert alert-danger d-none" role="alert">
                                    <span id="error-message-price-product"></span>
                                </div>
                                <label for="product-price" class="form-label">Precio del producto</label>
                                <input type="number" class="form-control" id="product-price"
                                step="0.00"
                                min="0"
                                    aria-describedby="productPriceHelp">
                                <div id="productPriceHelp" class="form-text">Mas de 0.
                                </div>
                            </div>
                            <div class="mb-3">
                                <div id="alert-message-stock-product" class="alert alert-danger d-none" role="alert">
                                    <span id="error-message-stock-product"></span>
                                </div>
                                <label for="product-stock" class="form-label">Stock del producto</label>
                                <input type="number" class="form-control" id="product-stock"
                                    aria-describedby="productStockHelp">
                                <div id="productStockHelp" class="form-text">Máximo 999999.
                                </div>
                            </div>
                            <div class="mb-3">
                                <div id="alert-message-active-product" class="alert alert-danger d-none" role="alert">
                                    <span id="error-message-active-product"></span>
                                </div>
                                <input type="checkbox" class="form-check-input" id="product-active">
                                <label class="form-check-label" for="product-active">Activo</label>
                            </div>
                            <div class="mb-3">
                                <label for="product-category" class="form-label">Categoría del producto</label>
                                <select class="form-select" aria-label="Categorías" id="product-category">
                                    <option value="">Selecciona una categoría</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary mt-2">Crear Producto</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="newCategory" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="nuevaCategoriaLabel">Nueva Categoría</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <form id="new-category-form">
                            <div class="mb-3">
                                <div id="alert-message-name-category" class="alert alert-danger d-none" role="alert">
                                    <span id="error-message-name-category"></span>
                                </div>
                                <label for="nameCategory" class="form-label">Nombre de la categoría</label>
                                <input type="text" class="form-control" id="category-name"
                                    aria-describedby="categoryNameHelp">
                                <div id="categoryNameHelp" class="form-text">Nombre de categoría unica.
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary mt-2">Crear Categoría</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>