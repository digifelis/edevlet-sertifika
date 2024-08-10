<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use PhpAmqpLib\Connection\AMQPStreamConnection;
use App\Http\Controllers\Admin\SertifikalarController;
use PhpAmqpLib\Message\AMQPMessage;

use PhpOffice\PhpWord\TemplateProcessor;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

use App\Models\superadmin\SertifikalarModal;


function ConsumerdenGelenSertifikaNo($sertifikaNo, $sertifikaId)
{
    $sertifikalar = SertifikalarModal::where('id', $sertifikaId)->first();
    $sertifikalar->sertifikaNo = $sertifikaNo;
    $sertifikalar->save();
    return response()->json(['message' => 'Sertifika No Güncellendi.'], 200);
}

function convertToPDF($filePath, $outputPath) {
    /* wordToPDF container service address */
    $url = 'http://10.1.2.55:3000/forms/libreoffice/convert';
    //$url = $GLOBALS["wordToPDFContainerServiceAddress"];

    // Initialize cURL session
    $curl = curl_init();

    // Set cURL options
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, [
        'files' => new \CURLFile($filePath),
    ]);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($curl, CURLOPT_VERBOSE, 1);

    // Execute cURL session and save the response to a file
    $response = curl_exec($curl);
    $error = curl_error($curl);

    // Check for cURL errors
    if ($error) {
        echo "cURL Error: $error";
    } else {
        // Save the PDF content to the output file
        file_put_contents($outputPath, $response);
      //  echo "Conversion successful. PDF saved at: $outputPath";
    }

    // Close cURL session
    curl_close($curl);
}


function convertSvgToJpg($filePath, $outputPath) {
    /* wordToPDF container service address */
    $curl = curl_init();

    curl_setopt_array($curl, array(
    CURLOPT_URL => 'http://10.1.2.55:8999/convert',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => file_get_contents($filePath),
    CURLOPT_HTTPHEADER => array(
        'Content-Type: image/svg+xml'
    ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    file_put_contents($outputPath, $response);
}

function RabbitMQdanBelgeOlustur($input_params, $basePath){
    print_r($input_params);
  //  echo ' [x] Received belgeOlustur1 ', $input_params["kurumId"], "\n";

    $sablon = $basePath . '/uploads/templates/' . $input_params["kurumId"] . '/' . $input_params["sablonDosyasi"];
    $templateProcessor = new TemplateProcessor($sablon);


    $completedSertifikaNoTemp = str_pad($input_params["lastInsertId"], 5, '0', STR_PAD_LEFT);
    $year = substr(date('Y'), -3);
    $dosyalamaKodu = 199;
    $birimId = 1;
    $completedSertifikaNo = $year.$dosyalamaKodu.$birimId.$completedSertifikaNoTemp;

 //   $completedSertifikaNo = str_pad($input_params["lastInsertId"], 12, '0', STR_PAD_LEFT);
    $sertifikaNo = 'UN_'.'04'.$input_params["kurumKodu"].$completedSertifikaNo;
    /*
    if($input_params["tur"] == "Kurs Belgesi"){
        $sertifikaNo = 'UN_'.'04'.$input_params["kurumKodu"].$completedSertifikaNo;
    }
    if($input_params["tur"] == "Katılım Belgesi"){
        $sertifikaNo = 'UN_'.'04'.$input_params["kurumKodu"].$completedSertifikaNo;
    }
    */

    $templateProcessor->setValue('kurumadI', $input_params["kurumAdi"]);
    $templateProcessor->setValue('ogrencıadı', $input_params["ogrenciAdi"]);
    $templateProcessor->setValue('ogrencısoyadı', $input_params["ogrenciSoyadi"]);
    $templateProcessor->setValue('kursAdi', $input_params["kursAdi"]);

    $templateProcessor->setValue('kursAdiIng', $input_params['kursAdiIng']);

    $templateProcessor->setValue('sertifikaNo', $sertifikaNo);
    $templateProcessor->setValue('tcKimlikNo', $input_params["tcKimlikNo"]);
    $templateProcessor->setValue('sertifikaAdi', $input_params["sertifikaAdi"]);
    $templateProcessor->setValue('baslik', $input_params["baslik"]);
    $templateProcessor->setValue('aciklama', $input_params["aciklama"]);
    $templateProcessor->setValue('baslangicTarihi', $input_params["baslangicTarihi"]);
    $templateProcessor->setValue('bitisTarihi', $input_params["bitisTarihi"]);

    $sertifikaGecerlilikTarihi = date('d.m.Y', strtotime($input_params['sertifikaGecerlilikTarihi']));
    $templateProcessor->setValue('sertifikaGecerlilikTarihi', $sertifikaGecerlilikTarihi);

    $templateProcessor->setValue('sertifikaTuru', $input_params["tur"]);
    $templateProcessor->setValue('sertifikaDili', $input_params["dilKey"]);


    $baseData = $input_params["kurumId"].'_'.$input_params["kursId"].'_'.$input_params["lastInsertId"];

    $file = $basePath.'/uploads/'.$baseData.'_qrcode.svg';
    $QrCodeText = 'barkodlubelgedogrulama://barkod: '.$sertifikaNo.';tckn:'.$input_params["tcKimlikNo"];
    QrCode::format('svg')->generate($QrCodeText, $file);
    
    convertSvgToJpg($file, $basePath.'/uploads/'.$baseData.'_qrcode.jpg');
    sleep(1);
    $qrImage = [$basePath.'/uploads/'.$baseData.'_qrcode.jpg', $file];
    $templateProcessor->setImageValue('foto', array('path' => $qrImage[0], 'width' => 100, 'height' => 100, 'ratio' => true));
    sleep(1);
    unlink($qrImage[0]);
    unlink($qrImage[1]);

    $wordOutputPath = $basePath.'/uploads/sertifikalar/'.$input_params["kurumId"].'/'.$input_params["kursId"].'/'.$input_params["lastInsertId"];
    if (!file_exists($wordOutputPath)) {
        mkdir($wordOutputPath, 0777, true);
    }

    $templateProcessor->saveAs($wordOutputPath. '/belge.docx');
    convertToPDF($wordOutputPath. '/belge.docx' , $wordOutputPath. '/belge.pdf');
    ConsumerdenGelenSertifikaNo($sertifikaNo, $input_params["lastInsertId"]);
    return [$wordOutputPath. '/belge.pdf', $sertifikaNo];

}


// RabbitMQ server configuration
$host = '10.1.2.55';
$port = '5672';
$user = 'guest';
$pass = 'guest';
$queueName = 'olusturulacaksertifikalar';

// Establish a connection to RabbitMQ server
$connection = new AMQPStreamConnection($host, $port, $user, $pass);

// Create a channel
$channel = $connection->channel();

// Declare the queue to ensure it exists
$channel->queue_declare($queueName, false, true, false, false);

echo " [*] Waiting for messages. To exit, press CTRL+C\n";

// Callback function to process the received message
$callback = function ($message) {
    $gelen = json_decode($message->body, true);
  //  print_r($gelen);

    echo ' [x] Received ', $gelen["ogrenciId"], "\n";

    $basePath = public_path();
    RabbitMQdanBelgeOlustur($gelen, $basePath);

    // Acknowledge the message
    $message->delivery_info['channel']->basic_ack($message->delivery_info['delivery_tag']);
};

// Set the callback function for incoming messages
$channel->basic_consume($queueName, '', false, false, false, false, $callback);

// Wait for incoming messages
while (count($channel->callbacks)) {
    $channel->wait();
}

// Close the channel and connection
$channel->close();
$connection->close();
