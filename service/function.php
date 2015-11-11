<?php
session_start();
if (isset($_GET['tag']) && $_GET['tag'] != '') 
{
    // GET tag
    $tag = $_GET['tag'];

	require_once 'include/DB_Functions.php';
	$db = new DB_Functions();

	if ($tag == 'login') 
    {
        // Request type is check Login
        $login = $_POST['login'];
        $password = $_POST['password'];

        // check for user
        $user = $db->getUserByLoginAndPassword($login, $password);
        if ($user != false) 
        {
            // user found
            $response["error"] = FALSE;
            $response["UserId"] = $user["UserId"];
            $response["user"]["login"] = $user["login"];
            $_SESSION['login'] = $user['login'];
            if($login == "admin")
            {
                header('Location: http://localhost/angular-shop/indexAdmin.html');
            }
            else
            {
                header('Location: http://localhost/angular-shop/indexUser.html');
            }
            
        } 
        else 
        {
            // user not found
            // echo json with error = 1
            $response["error"] = TRUE;
            $response["error_msg"] = "Mauvais login ou mot de passe!";
            echo json_encode($response);
        }
    }
	elseif ($tag == 'productList')
	{
		$listProduct = $db->getProductList();

        echo "{ \"books\": ".json_encode($listProduct)." }";
    }
    elseif ($tag == 'deletProduct')
	{
		$ProductId = $_GET['ProductId'];
		$db->deletProduct($ProductId);
    }
    elseif ($tag == "addToCart")
    {
        $ProductId = $_POST['ProductId'];
        $db->addToCart($ProductId);
    }
    else
    {
    	$response["error"] = TRUE;
    	$response["error_msg"] = "Aucun produit pour le moment  !'";
        echo json_encode($response);
    }

    // if ($tag == 'cartQuantity')
    // {

    // }
}
else 
{
    $response["error"] = TRUE;
    $response["error_msg"] = "Required parameter 'tag' is missing!";
    echo json_encode($response);
}
?>