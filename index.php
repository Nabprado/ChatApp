<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <title>News Search</title>
</head>
<body>
    <h1>News Search</h1>
    <div class="search">
    <form class="search_form" method="GET">
        <label for="search"><i class="fa-brands fa-searchengin"></i></label>
        <input type="text" id="search" name="search" />
        <input type="submit" id="submit" value="Search" />
    </form>
    </div>

    <div class="results">

        <?php
        if (isset($_GET['search']) && $_GET['search'] != '') :
            $search = $_GET['search'];
            $curl = curl_init();

            curl_setopt_array($curl, [
                CURLOPT_URL => "https://contextualwebsearch-websearch-v1.p.rapidapi.com/api/search/NewsSearchAPI?q=" . $search . "&pageNumber=1&pageSize=10&autoCorrect=true&fromPublishedDate=null&toPublishedDate=null",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => [
                    "X-RapidAPI-Host: contextualwebsearch-websearch-v1.p.rapidapi.com",
                    "X-RapidAPI-Key: xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx",
                    'Content-Type: application/json'
                ],
            ]);

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
                echo "cURL Error #:" . $err;
            } else {
                function jsonp_decode($jsonp, $assoc = false)
                { 
                    if ($jsonp[0] !== '[' && $jsonp[0] !== '{') {
                        $jsonp = substr($jsonp, strpos($jsonp, '('));
                    }
                    return json_decode(trim($jsonp, '();'), $assoc);
                }

                $results = jsonp_decode($response, true);
                $news = $results['value'];
            }


            // RESPONSE

            if (!empty($news)) {?>
                <h2>Résultats de la recherche :</h2>
                <?php foreach ($news as $post) {?>
                <div class="result">
                    <h3><?=$post['title']?></h3>
                    <a href="<?=$post['url']?>">Accéder à la source</a>
                    <p>Publié le <?=$post['datePublished']?></p>
                    <p><?=$post['body']?></p>
                </div>
                <?php }
            }
        endif;
        ?>

    </div>

</body>

</html>