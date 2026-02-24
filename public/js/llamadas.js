$(document).ready(function () {
    fetchProducts(true);

    fetchCategories();

    $("#flexSwitchCheckChecked").change(function () {
        let isChecked = $(this).is(":checked") ? true : false;
        fetchProducts(
            isChecked,
            1,
            $("#category-filter").val() ? $("#category-filter").val() : "",
        );
    });

    $("#category-filter").change(function () {
        let selectedCategoryId = $(this).val();
        fetchProducts(
            $("#flexSwitchCheckChecked").is(":checked") ? true : false,
            1,
            selectedCategoryId,
        );
    });

    $("#btn-new-product").click(function () {
        if ($("#category-filter option").length <= 1) {
            setTimeout(() => {
                $("#error-message").text("");
                $("#alert-message").addClass("d-none");
            }, 3000);
            $("#error-message").text(
                "Debe crear una categoría antes de agregar un producto.",
            );
            $("#alert-message").removeClass("d-none");
        } else {
            $("#newProduct").modal("show");
        }
    });

    $("#btn-new-category").click(function () {
        $("#newCategory").modal("show");
    });

    $("#new-product-form").submit(function (e) {
        e.preventDefault();
        e.stopPropagation();
        let productName = $("#product-name").val();
        let productPrice = parseFloat($("#product-price").val());
        let productStock = parseInt($("#product-stock").val());
        let productActive = $("#product-active").is(":checked") ? true : false;
        let productCategoryId = $("#product-category").val();

        $.ajax({
            url: "/api/products",
            method: "POST",
            data: JSON.stringify({
                name: productName,
                price: productPrice,
                stock: productStock,
                active: productActive,
                category_id: productCategoryId,
            }),
            contentType: "application/json",
            success: function (data) {
                $("#newProduct").modal("hide");
                $("#new-product-form")[0].reset();
                $("#flexSwitchCheckChecked")
                    .prop("checked", productActive)
                    .trigger("change");
                $("#category-filter").val(productCategoryId).trigger("change");
            },
            error: function (xhr, status, error) {
                if (xhr.status === 422) {
                    let errores = xhr.responseJSON.errors;
                    let message = "";

                    $.each(errores, function (key, value) {
                        let field = key.replace("_id", "");
                        $(`#error-message-${field}-product`).text(value[0]);
                        $(`#alert-message-${field}-product`).removeClass(
                            "d-none",
                        );
                    });
                    setTimeout(() => {
                        $("[id^='error-message']").text("");
                        $("[id^='alert-message']").addClass("d-none");
                    }, 3000);
                }
            },
        });
    });

    $("#new-category-form").submit(function (e) {
        e.preventDefault();
        let categoryName = $("#category-name").val();
        $.ajax({
            url: "/api/categories",
            method: "POST",
            data: JSON.stringify({
                name: categoryName,
            }),
            contentType: "application/json",
            success: function (data) {
                $("#newCategory").modal("hide");
                $("#category-name").val("");
                fetchCategories();
            },
            error: function (xhr, status, error) {
                if (xhr.status === 422) {
                    let errores = xhr.responseJSON.errors;
                    let message = errores.name ? errores.name[0] : "";
                    console.error("Validation errors:", errores);
                    if (errores.name) {
                        $("#error-message-name-category").text(message);
                        $("#alert-message-name-category").removeClass("d-none");
                        setTimeout(() => {
                            $("#error-message-name-category").text("");
                            $("#alert-message-name-category").addClass(
                                "d-none",
                            );
                        }, 3000);
                    }
                }
            },
        });
    });
});

function toggleProductStatus(productId, active) {
    $.ajax({
        url: "/api/products/" + productId,
        method: "PUT",
        data: JSON.stringify({
            active: active,
        }),
        contentType: "application/json",
        success: function (data) {
            fetchProducts(
                $("#flexSwitchCheckChecked").is(":checked") ? true : false,
            );
        },
        error: function (error) {
            console.error("Error toggling product status:", error);
        },
    });
}

function fetchCategories() {
    $.ajax({
        url: "/api/categories",
        method: "GET",
        success: function (data) {
            let categoriesList = $("#category-filter");
            let categoriesListModal = $("#product-category");
            categoriesList.empty();
            categoriesListModal.empty();
            categoriesList.append(
                `<option value="">Todas las categorías</option>`,
            );
            data.forEach((category) => {
                categoriesList.append(
                    `<option value="${category.id}">${category.name}</option>`,
                );
                categoriesListModal.append(
                    `<option value="${category.id}">${category.name}</option>`,
                );
            });
        },
        error: function (error) {
            console.error("Error fetching categories:", error);
        },
    });
}

function fetchProducts(activeOnly, page = 1, categoryId = "") {
    $.ajax({
        url:
            "/api/products?active=" +
            activeOnly +
            "&page=" +
            page +
            (categoryId ? "&category_id=" + categoryId : ""),
        method: "GET",
        success: function (data) {
            let productsBody = $("#products-body");
            productsBody.empty();
            data.data.forEach((product) => {
                var changeActive = product.active ? false : true;

                productsBody.append(`
                                <tr>
                                    <td>${product.name}</td>
                                    <td>${product.price}</td>
                                    <td>${product.stock}</td>
                                    <td>${product.category.name}</td>
                                    <td>${product.active ? "Activo" : "Inactivo"}</td>
                                    <td><button class="btn btn-sm btn-outline-primary" onclick="toggleProductStatus(${product.id}, ${changeActive})">Editar estado a ${changeActive ? "Activo" : "Inactivo"}</button></td>
                                </tr>
                            `);
            });

            // Manejar paginación
            let paginationLinks = $("#paginate-links");
            paginationLinks.empty();
            if (data.prev_page_url) {
                paginationLinks.append(
                    `<button class="btn btn-primary" onclick="fetchProducts(${activeOnly}, ${data.current_page - 1})">Anterior</button>`,
                );
            }
            if (data.next_page_url) {
                paginationLinks.append(
                    `<button class="btn btn-primary" onclick="fetchProducts(${activeOnly}, ${data.current_page + 1})">Siguiente</button>`,
                );
            }
        },
        error: function (error) {
            console.error("Error fetching products:", error);
        },
    });
}
