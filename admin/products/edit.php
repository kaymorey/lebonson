<?php
	require '../../conf/top.php';
	$template = $tpl->loadTemplate('admin/products/edit.html.twig');

	session_start();
	if(!isset($_SESSION['admin'])) {
		header('Location: ../login.php');
	}
	if(!isset($_GET['product']) || !is_numeric($_GET['product'])) {
		header('Location: index.php');
	}
	$idProduct = $_GET['product'];
	$product = getProductById($idProduct);
	if(!$product) {
		header('Location: index.php');
	}

	$artists = getAllArtists();
	$categories = getAllCategories();

	$errors = array();

	if($_SERVER['REQUEST_METHOD'] == 'POST') {
		if(!isset($_POST['title']) || !isset($_POST['price']) || !isset($_POST['category']) || !isset($_POST['artist']) || !isset($_FILES['image']) || !isset($_POST['editor']) || !isset($_POST['date']) || !isset($_POST['description']) || !isset($_POST['stock'])) {
			$errors[] = 'Formulaire non conforme.';
		}
		else {
			$title = $_POST['title'];
			$price = $_POST['price'];
			$category = $_POST['category'];
			$artist = $_POST['artist'];
			$image = $_FILES['image']['name'];
			$editor = $_POST['editor'];
			$date = $_POST['date'];
			$description = $_POST['description'];
			$stock = $_POST['stock'];

			// Supprimer les balises HTML et PHP
			$title = strip_tags($title);
			$price = strip_tags($price);
			$editor = strip_tags($editor);
			$description = strip_tags($description);

			// Supprimer les espaces en début et fin de chaine
			$title = trim($title);
			$price = trim($price);
			$editor = trim($editor);
			$description = trim($description);

			// Vérifier que les champs obligatoires ne sont pas vides
			if(empty($title) || empty($price) || empty($category) || empty($artist) || empty($date) || empty($stock)) {
				$errors[] = 'Veuillez remplir tous les champs suivis d\'un astérisque.'; 
			}
			else {
				// Vérifier que le prix est au bon format
				$regexPrix = "#^[0-9]{1,4}\.[0-9]{2}$#";
				if(!preg_match($regexPrix, $price)) {
					$errors[] = 'Le prix n\'est pas au bon format, veuillez entrer un prix entre 1.00 et 9999.00.';
				}
				// Vérifier que la catégorie existe
				if(!is_numeric($category)) {
					$errors[] = 'La catégorie spécifiée n\'existe pas.';
				}
				else if(!getCategoryById($category)) {
					$errors[] = 'La catégorie spécifiée n\'existe pas.';	
				}

				// Vérifier que l'artiste existe
				if(!is_numeric($artist)) {
					$errors[] = 'L\'artiste spécifié n\'existe pas.';
				}
				else if(!getArtistById($artist)) {
					$errors[] = 'L\'artiste spécifié n\'existe pas.';
				}

				// Vérifier que la date est au bon format (jj/mm/yyyy)
				$regexDate = "#^0[1-9]|1[0-9]|2[0-9]|3[0-1]/0[1-9]|1[1-2]/19[0-9]{2}|20[0-9]{2}$#";
				if(!preg_match($regexDate, $date)) {
					$errors[] = 'La date doit être au format jj/mm/yyyy.';
				}
				else {
					// Vérifier que la date existe
					$dateProduct = explode("/", $date);
					$day = $dateProduct['0'];
					$month = $dateProduct['1'];
					$year = $dateProduct['2'];
					if(!checkdate($month, $day, $year)) {
						$errors[] = 'La date spécifiée n\'existe pas.';
					}
					else {
						$date = new DateTime($year.'-'.$month.'-'.$day);
					}
				}

				//  Vérifier que le champ stock vaut 1 ou 0
				if($stock != "true" && $stock != "false") {
					$errors[] = 'Le champ état doit valoir <em>En stock</em> ou <em>Épuisé</em>.';
				}

				// Vérifier que le produit n'existe pas déjà en base (en omettant le produit actuel)
				// false - n'existe pas en base
				// default - Produit avec même titre présent en base
				// true - Produit avec même titre et même artiste présent en base
				$isProduct = isProduct($title, $artist, $idProduct);
				switch($isProduct) {
					case "true" :
						$errors[] = 'Ce produit est déjà répertorié dans la base de données.';
						break;
					case "false" :
						break;
					default :
						//$countName = $isProduct;
						break;
				}

				// Récupérer le fichier
				$source = $_FILES['image']['tmp_name'];
				$currImage = $product['image'];
				$currTitle = $product['title'];
				$currArtist = $product['id_artist'];
				$currSlug = $product['slug'];
				
				$idArtist = $artist;
				$artist = getArtistById($idArtist);

				// Si on a modifié le titre ou l'artiste du produit --> on modifie le filename
				if($currTitle != $title || $currArtist != $idArtist) {
					// Je récupère mon extension
					if(!empty($source)) {
						$filename = $_FILES['image']['name'];
					}
					else {
						$filename = $currImage;
					}
					$extension = strstr($filename, '.');

					if(!empty($artist['firstname'])) {
						$filename = slug($artist['firstname']).'-'.slug($artist['lastname']);
					}
					else {
						$filename = slug($artist['lastname']);
					}
					$slug = $filename.'-'.slug($title);
					$filename .= '-'.slug($title).$extension;
				}
				// Sinon on donne le même nom qu'à l'ancienne image
				else {
					$filename = $currImage;
					$slug = $currSlug;
				}

				// Si on a ajouté une nouvelle image --> supression puis ajout
				if(!empty($source)) {
					$destination = '../../uploads/products/'.$filename;
					$imgTypes = array(IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_BMP);
					if (!in_array(exif_imagetype($source), $imgTypes)) {
						$errors[] = 'L\'image doit être au format jpg, png ou bmp.';
					}
					if(empty($errors)) {
						// Je supprime l'ancienne image
						if(!unlink("../../uploads/products/".$currImage)) {
							$errors[] = 'Erreur lors de la suppression de l\'ancienne image';
						}
						// Je transfère la nouvelle image
						else if(!move_uploaded_file($source, $destination)) {
							$errors[] = 'Erreur lors du transfert du fichier.';
						}
						// Je supprime les images créées par imagine
						else {
							$dir = '../../uploads/products';
							$currExtension = strstr($currImage, '.');
							$currFilename = strstr($currImage, '.', true);
							foreach(glob($dir.'/'.$currFilename.'_*x*'.$currExtension) as $file) {
								if(!unlink($file)) {
									$errors[] = 'Erreur lors de la suppression des images imagine';
								}
							}
						}
					}
				}
				// Sinon --> renommage
				else {
					if(empty($errors) && $currImage != $filename) {
						// Je renomme mon image
						if(!rename('../../uploads/products/'.$currImage, '../../uploads/products/'.$filename)) {
							$errors[] = 'Erreur lors du renommage de l\'image';
						}
						else {
							// Je renomme les images créées par imagine
							$dir = '../../uploads/products';
							$currExtension = strstr($currImage, '.');
							$currFilename = strstr($currImage, '.', true);

							$extension = strstr($filename, '.');
							$name = strstr($filename, '.', true);
							// Pour tous les fichiers du type filename_*x*.extension
							foreach(glob($dir.'/'.$currFilename.'_*x*'.$currExtension) as $file) {
								$regex = "#^\.\./\.\./uploads/products/[^\\/:\?\"<>\|]+_([0-9]+)x([0-9]+)\.[a-z]{3}$#";
								preg_match($regex, $file, $match);
								// On récupère la largeur
								$width = $match[1];
								// On récupère la hauteur
								$height = $match[2];
								$newFilename = $dir.'/'.$name.'_'.$width.'x'.$height.$extension;
								if(!rename($file, $newFilename)) {
									$errors[] = 'Erreur lors du renommage des images imagine';
								}
							}
						}
					}
				}

				// Ajout en base
				if(empty($errors)) {
					if($stock == "true") {
						$stock = 1;
					}
					else {
						$stock = 0;
					}
					$editProduct = editProduct($idProduct, $idArtist, $category, $title, $slug, $price, $filename, $editor, $date->format('Y-m-d'), $description, $stock);
					if(!$editProduct) {
						$errors[] = 'Erreur lors de l\'exécution de la requête.';
					}
					else {
						header('Location: index.php?edit='.$idProduct);
					}

				}
			}
		}
	}

	echo($template->render(array(
		'MEDIA_PATH' => MEDIA_PATH,
		'categoryMenu' => 'products',
		'root' => '../../',
		'errors' => $errors,
		'artists' => $artists,
		'categories' => $categories,
		'product' => $product
	)));