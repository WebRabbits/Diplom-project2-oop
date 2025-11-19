<?php 

require_once('vendor/autoload.php');
use App\Models\ValueObject\Post\ImagePost;

try {
    $image1 = new ImagePost('/test.png', 'image/png');
    echo "VO ImagePost_1 работает =>>" . $image1->getValue() . "\n";
} catch (Exception $e) {
    echo "Ошибка >>> ". $e->getMessage() . "\n";
}

try {
    $image2 = new ImagePost('/test.jpeg', 'image/jpeg');
    echo "VO ImagePost_2 работает =>>" . $image2->getValue() . "\n";
} catch (Exception $e) {
    echo "Ошибка >>> ". $e->getMessage() . "\n";
}

try {
    $image3 = new ImagePost('/test.png', 'image/jif');
    echo "VO ImagePost_3 работает =>>" . $image3->getValue() , "\n";
} catch (Exception $e) {
    echo "Ошибка >>> ". $e->getMessage() . "\n";
}

try {
    $image4 = new ImagePost('', 'image/jif');
    echo "VO ImagePost_4 работает =>>" . $image3->getValue() , "\n";
} catch (Exception $e) {
    echo "Ошибка >>> ". $e->getMessage() . "\n";
}

try {
    $image5 = new ImagePost("/test1.png", "image/png");
    $image6 = new ImagePost("/test1.png", "image/png");

    if($image5->equals($image6)) {
        echo "Объекты совпадают" . "\n";
    } else {
        echo "Объекты НЕ совпадают" . "\n";
    }
} catch (Exception $e) {
    echo "Ошибка >>> " . $e->getMessage() . "\n";
}

try {
    $image7 = new ImagePost("/test2.png", "image/png");
    $image8 = new ImagePost("/test1.png", "image/jpeg");

    if($image7->equals($image8)) {
        echo "Объекты совпадают" . "\n";
    } else {
        echo "Объекты НЕ совпадают" . "\n";
    }
} catch (Exception $e) {
    echo "Ошибка >>> " . $e->getMessage() . "\n";
}

?>