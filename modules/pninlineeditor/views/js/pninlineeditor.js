/*
* Module Created by PrestaNitro info @ prestanitro . com
* You are not allowed to resell or redistribute this module.
*/

var pnInlineEditorInit = function () {

    if (pninlineeditor_en_nombre) {
        $(pninlineeditor_nombre).attr("contenteditable", "true");
        $(pninlineeditor_nombre).addClass("pninlineeditor_editable");

    }
    if (pninlineeditor_en_precio) {
        $(pninlineeditor_precio).attr("contenteditable", "true");
        $(pninlineeditor_precio).addClass("pninlineeditor_editable");
    }
    if (pninlineeditor_en_referencia) {
        $(pninlineeditor_referencia).attr("contenteditable", "true");
        $(pninlineeditor_referencia).addClass("pninlineeditor_editable");
    }
    if (pninlineeditor_en_desc_corta) {
       $(pninlineeditor_desc_corta).attr("contenteditable", "true");
       $(pninlineeditor_desc_corta).addClass("pninlineeditor_editable");
    }
    if (pninlineeditor_en_desc) {
        $(pninlineeditor_desc).attr("contenteditable", "true");
        $(pninlineeditor_desc).addClass("pninlineeditor_editable");
    }
    if (pninlineeditor_en_cantidad) {
        $(pninlineeditor_cantidad).attr("contenteditable", "true");
        $(pninlineeditor_cantidad).addClass("pninlineeditor_editable");
    }





    var targetNodes = $("*[contenteditable=true]");
    var MutationObserver = window.MutationObserver || window.WebKitMutationObserver;
    var myObserver = new MutationObserver(mutationHandler);
    var obsConfig = { childList: true, characterData: true, attributes: true, subtree: true };

    //Añade un manejador de mutación, para detectar los cambios y colorear el boton de guardar
    targetNodes.each(function () {
        myObserver.observe(this, obsConfig);
    });
    function mutationHandler(mutationRecords) {
        mutationRecords.forEach(function (mutation) {
            if (mutation.type == "characterData") {

                $("#pninlineeditor-submit").addClass("pnverde");
            }
        });
    }


    $("#pninlineeditor-submit").click(function () {
        var nombre = $(pninlineeditor_nombre).html();
        var referencia = $(pninlineeditor_referencia).html();
        var precio = $(pninlineeditor_precio).html();
        var descripcion_corta = $(pninlineeditor_desc_corta).html();
        var descripcion = $(pninlineeditor_desc).html();
        var id_product = $("#product_page_product_id").val();
        var id_product_attribute = $("#idCombination").val();
        var cantidad = $(pninlineeditor_cantidad).text();
        var red_amount = $(pninlineeditor_red_amount).text();
        var red_percent = $(pninlineeditor_red_percent).text();


        if (pninlineeditor_en_desc_corta && (descripcion_corta.length > pninlineeditor_desc_corta_limit) && (pninlineeditor_desc_corta_limit>0) ) {
            alert(pninlineeditor_desc_error);
            return;
        }

        // Name doesn't accept &amp;
        nombre = nombre.replace("&amp;", "&");

        precio = precio.replace(/[^0-9,.]/g, '');
        // If its a big number which contains , and .
        if(precio.indexOf(',')!=-1 || precio.indexOf('.')!=-1){
            // Big prices can have two differents formats
            if(pninlineditor_formato) {
                precio = precio.replace(".", "");
                precio = precio.replace(",", ".");
            } else {
                precio = precio.replace(",", "");
            }
        }

        precio = parseFloat(precio);
        if (pninlineeditor_impuestos) {
            // Elimina impuestos
            precio = (precio * 100) / (100 + taxRate);
        }
        // Aumenta la precision a 6 para mejorar el redondeo.
        precio = precio.toFixed(6);


        // Reduction amount
        if (red_amount != null && red_amount != '') {
            red_amount = red_amount.replace(/[^0-9-,-.]/g, '');
            if(red_amount.indexOf(',')!=-1 && red_amount.indexOf('.')!=-1){
                // Big prices can have two differents formats
                if(pninlineditor_formato) {
                    red_amount = red_amount.replace(".", "");
                } else
                    red_amount = red_amount.replace(",", "");
            }
            red_amount = red_amount.replace(",", ".");
            red_amount = red_amount.replace("-", "");
            red_amount = parseFloat(red_amount);
            // Elimina impuestos
            red_amount = (red_amount * 100) / (100 + taxRate);
            red_amount = red_amount.toFixed(6);
        }

        // Reduction percent
        if (red_percent != null && red_percent != '') {
            red_percent = red_percent.replace(/[^0-9-,-.]/g, '');
            if(red_percent.indexOf(',')!=-1 && red_percent.indexOf('.')!=-1){
                // Big prices can have two differents formats
                if(pninlineditor_formato) {
                    red_percent = red_percent.replace(".", "");
                } else
                    red_percent = red_percent.replace(",", "");
            }
            red_percent = red_percent.replace(",", ".");
            red_percent = red_percent.replace(",", ".");
            red_percent = red_percent.replace("-", "");
            red_percent = parseFloat(red_percent);
            red_percent = red_percent.toFixed(2);
        }
        console.log("id_product:"+id_product);
        console.log("id_product_attribute:"+id_product_attribute);
        console.log("name:"+nombre);
        console.log("reference:"+referencia);
        console.log("qty:"+cantidad);
        console.log("price:"+precio);
        console.log("taxRate:"+taxRate);
        console.log("short_description:"+descripcion_corta);
        console.log("description:"+descripcion);
        console.log("red_amount:"+red_amount);
        console.log("red_percent:"+red_percent);


        $("#pninlineeditor-submit").addClass("pnverde");

        $("#pninlineeditor-submit").html(pninlineeditor_saving);
        $.ajax({
            type: "POST",
            cache: false,
            url: baseDir + "modules/pninlineeditor/ajax.php",
            data: {
                "id_product": id_product,
                "id_product_attribute": id_product_attribute,
                "nombre": nombre,
                "referencia": referencia,
                "precio": precio,
                "cantidad": cantidad,
                "descripcion": descripcion,
                "descripcion_corta": descripcion_corta,
                "red_amount": red_amount,
                "red_percent": red_percent
            },
            success: function (data) {
                console.log("data " + data);
                $("#pninlineeditor-submit").html(pninlineeditor_saved);
                location.reload();

            },
            error: function (data) {
                $("#pninlineeditor-submit").addClass("pnrojo");
                $("#pninlineeditor-submit").html(pninlineeditor_error);

            }

        });


    });

}


$(document).ready(pnInlineEditorInit);


