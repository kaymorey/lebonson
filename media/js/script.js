$(document).ready(function() {
	// Fonction suppression
	$('.fancy-remove').click(function() {
		var path = $(this).attr("href");

		$.fancybox.open({
			href : path,
			type : "ajax",
			closeBtn : false,
			afterShow : function() {
				$('.remove-container .btn-danger').click(function() {
					$.fancybox.close();
					return false;
				});
				$('.remove-container .btn-info').click(function() {
					$.ajax({
						url : $(this).attr("href"),
						type : "POST",
						success : function() {
							window.location = "index.php?remove=1";
						},
						error : function() {
							alert("Erreur lors de la suppression");
							$.fancybox.close();
						}
					});
					return false;
				});
			}
		});

		return false;
	});

	// Datepicker
	$('#datepicker').datepicker({
		format : 'dd/mm/yyyy',
		viewMode : 'years'
	});
	// Tri tableaux
	$('table.artists').tablesorter({
		headers: {
			4: {
				sorter: false
			}
		}
	});
	$('table.categories').tablesorter({
		headers: {
			2: {
				sorter: false
			}
		}
	});
	$('table.orders').tablesorter({
		headers: {
			5: {
				sorter: false
			}
		}
	});
	$('table.customers').tablesorter({
		headers: {
			3: {
				sorter: false
			}
		}
	});
	$('table.products').tablesorter({
		headers: {
			0: {
				sorter: false
			},
			5: {
				sorter: false
			}
		}
	});

	// ---- PANIER ----
	// Ajouter au panier
	$('.fancy-add-cart').click(function() {
		var path = $(this).attr("href");

		$.fancybox.open({
			href : path,
			type : "ajax",
			closeBtn : false,
			afterShow : function() {
				$('.add-cart .actions .order').click(function() {
					// Mise à jour du nombre de produits dans le panier - header
					var nbProductsCart = $('header .badge').text();
					if(nbProductsCart == "") {
						$('header .cart').append('<span class="badge badge-info">1</span>');
					}
					else {
						nbProductsCart = parseInt(nbProductsCart) + 1;
						$('header .badge').text(nbProductsCart);
					}

					$.fancybox.close();
					return false;
				});
			}
		});

		return false;
	});

	// Gestion des quantités
	$('table.cart .change-quantity').click(function() {
		var totalPriceCart = $('div.cart .summary .total-price-cart').text();
		var nbProductsCart = $('header .badge').text();

		totalPriceCart = parseFloat(totalPriceCart);
		nbProductsCart = parseInt(nbProductsCart);

		var product = $(this).parent().attr("data-id");

		// Diminution de la quantité
		if($(this).hasClass('minus')) {
			// On récupère la quantité et le prix du produit
			var quantity = $(this).next('.quantity').val();
			var price = $(this).parent().next('.td-price').find('.price').text();
			var totalPrice = 0;
		
			quantity = parseInt(quantity);
			price = parseFloat(price);
		
			if(quantity > 1) {
				quantity -= 1;
				nbProductsCart -= 1;
				// Prix total du produit
				totalPrice = quantity * price;
				// Arrondir à 2 chiffres après la virgule
				totalPrice = totalPrice.toFixed(2);
				// Prix total du panier
				totalPriceCart -= price;
				totalPriceCart = totalPriceCart.toFixed(2);

				// On applique les changements
				$(this).next('.quantity').val(quantity);
				$(this).parent().siblings('.td-total-price').find('.total-price').text(totalPrice);
			}
			else if(quantity == 1) {
				removeFromCart($(this));
			}
		}
		// Augmentation de la quantité
		else if($(this).hasClass('plus'))  {
			var quantity = $(this).prev('.quantity').val();
			var price = $(this).parent().next('.td-price').find('.price').text();
			var totalPrice = 0;

			quantity = parseInt(quantity);
			price = parseFloat(price);
			totalPriceCart = parseFloat(totalPriceCart);

			if(quantity < 999) {
				quantity += 1;
				nbProductsCart += 1;
				totalPrice = quantity * price;
				totalPrice = totalPrice.toFixed(2);
				totalPriceCart += price;
				totalPriceCart = totalPriceCart.toFixed(2);

				$(this).prev('.quantity').val(quantity);
				$(this).parent().siblings('.td-total-price').find('.total-price').text(totalPrice);
			}
		}
		// On modifie la variable de session panier
		$.ajax({
			url : "cart.php",
			type : "GET",
			data : {
				changeQuantity : product,
				quantity : quantity
			}
		});
		// On modifie le prix total du panier
		$('div.cart .summary .total-price-cart').text(totalPriceCart);
		// On modifie le nombre de produits du panier dans le header
		$('header .badge').text(nbProductsCart);
		
		return false;
	});
	// Changement de la quantité via le champ input
	actualQuantity = $('table.cart .quantity').val();
	$('table.cart .quantity').change(function() {
		var quantity = parseInt($(this).val());
		if(isNaN(quantity) || quantity < 0 || quantity > 999) {
			$(this).val(actualQuantity);
		}
		else if(quantity == 0) {
			removeFromCart($(this));	
		}
		else {
			var product = $(this).parent().attr("data-id");
			var price = $(this).parent().next('.td-price').find('.price').text();
			var totalPrice = $(this).parent().siblings('.td-total-price').find('.total-price').text();
			var totalPriceCart = $('div.cart .summary .total-price-cart').text();

			price = parseFloat(price);
			totalPrice = parseFloat(totalPrice);
			totalPriceCart = parseFloat(totalPriceCart);

			totalPriceCart -= totalPrice;
			totalPrice = quantity * price;
			totalPriceCart += totalPrice;
			totalPriceCart = totalPriceCart.toFixed(2);

			$(this).parent().siblings('.td-total-price').find('.total-price').text(totalPrice);
			$('div.cart .summary .total-price-cart').text(totalPriceCart);

			actualQuantity = $('table.cart .quantity').val();

			$.ajax({
				url : "cart.php",
				type : "GET",
				data : {
					changeQuantity : product,
					quantity : quantity
				}
			}).done(function(nbProductsCart) {
				if(nbProductsCart > 0) {
					$('header .badge').text(nbProductsCart);
				}
				else {
					$('div.cart').html('<h3>Mon panier</h3><p>Votre panier est vide.</p>');
					return;
				}
			});
		}
	});


	// Suppression d'un produit
	$('table.cart .remove-product').click(function() {
		removeFromCart($(this));
		return false;
	});

	function removeFromCart(elmt) {
		var product = elmt.parent().attr("data-id");
		var price = 0;
		var totalPriceCart = 0;

		// Supprimer le produit du panier
		$.ajax({
			url : "cart.php",
			type : "GET",
			data : {
				remove : product
			}
		}).done(function(nbProductsCart) {
			if(nbProductsCart > 0) {
				$('header .badge').text(nbProductsCart);
			}
			else {
				$('header .badge').text('');
				$('div.cart').html('<h3>Mon panier</h3><p>Votre panier est vide.</p>');
				return;
			}
		});

		// Cacher la ligne du tableau correspondante
		elmt.parent().parent().hide("slow", function() {
			// Modifier le total du panier
			$('table.cart tr:visible .td-total-price').find('.total-price').each(function() {
				price = parseFloat($(this).text());
				totalPriceCart += price;
				totalPrice = totalPriceCart.toFixed(2);
				console.log(totalPriceCart);
			});
			$('div.cart .summary .total-price-cart').text(totalPriceCart);
		});
	}

	// Validation du panier
	$('div.cart .summary form .submit').click(function() {
		if($('div.cart .summary form .conditions').is(':checked') == false && $('div.cart .alert-error').length == 0) {
			$('div.cart .breadcrumb').after('<div class="alert alert-error">Veuillez accepter les conditions générales de vente.</div>');
		
			return false;
		}
	});
	// ---- FIN PANIER ----

	// Adresse de facturation
	$('form.address fieldset.billing').hide();
	$('form.address input.different-billing').click(function() {
		$('form.address fieldset.billing').toggle();
	});
});