<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\superadmin\SertifikalarModal;
use App\Models\superadmin\KursModal;
use App\Models\superadmin\OgrencilerModal;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpWord\TemplateProcessor;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

use App\Imports\SertifikalarImport;
use Maatwebsite\Excel\Facades\Excel;

use Exception;
class SertifikalarController extends Controller
{    
    /**
     * convertToPDF
     *
     * @param  mixed $filePath
     * @param  mixed $outputPath
     * @return void
     */
    public function convertToPDF($filePath, $outputPath) {
        /* wordToPDF container service address */
        $url = env('GOTENBERG_URL').'/forms/libreoffice/convert';
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
         //   echo "Conversion successful. PDF saved at: $outputPath";
        }
    
        // Close cURL session
        curl_close($curl);
    }

    public function convertSvgToJpg($filePath, $outputPath) {
        /* wordToPDF container service address */
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => env("SVGJPG_URL").'/convert',
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

    public function createQrcode($text, $baseData){
        $file = public_path('uploads/'.$baseData.'_qrcode.svg');
        QrCode::format('svg')->generate($text, $file);
 
        $this->convertSvgToJpg($file, public_path('uploads/'.$baseData.'_qrcode.jpg'));

        return [public_path('uploads/'.$baseData.'_qrcode.jpg'), $file];
    }

    /**
     * belgeOlustur
     *
     * @param  mixed $input_params
     * @return void
     */
    public function belgeOlustur($lastInsertId, $userInstitution, $kursID, $ogrenciID){
        $input_params = DB::table('sertifikalar_modals')
        ->join('kurum_modals', 'kurum_modals.id', '=', 'sertifikalar_modals.kurumId')
        ->join('kurs_modals', 'kurs_modals.id', '=', 'sertifikalar_modals.kursId')
        ->join('ogrenciler_modals', 'ogrenciler_modals.id', '=', 'sertifikalar_modals.ogrenciId')
        ->select('sertifikalar_modals.*', 'kurum_modals.kurumAdi', 'kurum_modals.kurumKodu',
        'kurs_modals.*', 
        'ogrenciler_modals.tcKimlikNo', 'ogrenciler_modals.ogrenciAdi', 'ogrenciler_modals.ogrenciSoyadi')
        ->where('sertifikalar_modals.kurumId', '=', $userInstitution)
        ->where('sertifikalar_modals.kursId', '=', $kursID)
        ->where('sertifikalar_modals.ogrenciId', '=', $ogrenciID)
        ->get();
        $input_params = $input_params[0];
        //dd($input_params);
        /* belge oluşturulacak ve belge dosya olarak create edildikten sonra belge yolunu döndürecek */
        //$phpWord = new PhpWord();
        /* define new template */
        $sablon =  public_path("uploads/templates/".$input_params->kurumId."/".$input_params->sablonDosyasi);
        $templateProcessor = new TemplateProcessor($sablon);
        //$templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor("sablon1.docx");
        /* create sertifikaNo */
        /* format of sertifikaNo: UN_041085YYYYYYYYYYYY */
        /* complete lastinsertid to 4 digit */
        /* get lenght of $lastInsertId */
        
        $completedSertifikaNoTemp = str_pad($lastInsertId, 5, '0', STR_PAD_LEFT);
        $year = substr(date('Y'), -3);
        $dosyalamaKodu = 199;
        $birimId = 1;
        $completedSertifikaNo = $year.$dosyalamaKodu.$birimId.$completedSertifikaNoTemp;

        if($input_params->tur == "Kurs Belgesi"){
            $sertifikaNo = 'UN_'.'04'.$input_params->kurumKodu.$completedSertifikaNo;
        }
        if($input_params->tur == "Katılım Belgesi"){
            $sertifikaNo = 'UN_'.'04'.$input_params->kurumKodu.$completedSertifikaNo;
        }
        $sertifikalar = SertifikalarModal::where('id', $lastInsertId)->where('kurumId', $userInstitution)->first();
        $sertifikalar->sertifikaNo = $sertifikaNo;
        $sertifikalar->save();
        /* set values to template */
        $templateProcessor->setValue('kurumadI', $input_params->kurumAdi);
        $templateProcessor->setValue('ogrencıadı', $input_params->ogrenciAdi);
        $templateProcessor->setValue('ogrencısoyadı', $input_params->ogrenciSoyadi);
        $templateProcessor->setValue('kursAdi', $input_params->kursAdi);
        $templateProcessor->setValue('kursAdiIng', $input_params->kursAdiIng);
        $templateProcessor->setValue('sertifikaNo', $sertifikaNo);
        $templateProcessor->setValue('tcKimlikNo', $input_params->tcKimlikNo);
        $templateProcessor->setValue('sertifikaAdi', $input_params->sertifikaAdi);
        $templateProcessor->setValue('baslik', $input_params->baslik);
        $templateProcessor->setValue('aciklama', $input_params->aciklama);
        $templateProcessor->setValue('baslangicTarihi', $input_params->baslangicTarihi);
        $templateProcessor->setValue('bitisTarihi', $input_params->bitisTarihi);

        $sertifikaGecerlilikTarihi = date('d.m.Y', strtotime($input_params->sertifikaGecerlilikTarihi));
        $templateProcessor->setValue('sertifikaGecerlilikTarihi', $sertifikaGecerlilikTarihi);
        $templateProcessor->setValue('sertifikaTuru', $input_params->tur);
        $templateProcessor->setValue('sertifikaDili', $input_params->dilKey);


        /* create qrcode */
        $baseData = $input_params->kurumId.'_'.$input_params->kursId.'_'.$lastInsertId;
	    $QrCodeText = 'barkodlubelgedogrulama://barkod: '.$sertifikaNo.';tckn:'.$input_params->tcKimlikNo;
        $qrImage = $this->createQrcode($QrCodeText, $baseData);
	    sleep(1);
        $templateProcessor->setImageValue('foto', array('path' => $qrImage[0], 'width' => 100, 'height' => 100, 'ratio' => true));
	    sleep(1);
        /* delete image files */
        unlink($qrImage[0]);
        unlink($qrImage[1]);

        /* create new folder for sertifika */
        $wordOutputPath = public_path('uploads/sertifikalar/'.$input_params->kurumId.'/'.$input_params->kursId.'/'.$lastInsertId);
        if (!file_exists($wordOutputPath)) {
            mkdir($wordOutputPath, 0777, true);
        }

        /* save template as docx file */
        $templateProcessor->saveAs($wordOutputPath. '/belge.docx');
	    sleep(1);
        /* convert docx to PDF */
        (new SertifikalarController())->convertToPDF($wordOutputPath. '/belge.docx' , $wordOutputPath. '/belge.pdf');
        
        return $wordOutputPath. '/belge.pdf';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */    
    public function index()
    {
        //
        $sertifikalar = DB::table('sertifikalar_modals')
        ->join('kurum_modals', 'kurum_modals.id', '=', 'sertifikalar_modals.kurumId')
        ->join('kurs_modals', 'kurs_modals.id', '=', 'sertifikalar_modals.kursId')
        ->join('ogrenciler_modals', 'ogrenciler_modals.id', '=', 'sertifikalar_modals.ogrenciId')
        ->select('sertifikalar_modals.*', 'kurum_modals.kurumAdi', 'kurs_modals.kursAdi', 'ogrenciler_modals.ogrenciAdi', 'ogrenciler_modals.ogrenciSoyadi')
        ->where('sertifikalar_modals.kurumId', '=', Auth::user()->userInstitution)
        ->get();
        return view('admin.sertifikalar.index', ['sertifikalar' => $sertifikalar]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $kurslar = KursModal::where('kursKurumId', Auth::user()->userInstitution)->get();
        $ogrenciler = OgrencilerModal::where('kurumId', Auth::user()->userInstitution)->get();
        return view('admin.sertifikalar.add', ['kurslar' => $kurslar, 'ogrenciler' => $ogrenciler, 'action' => Null]);
    }
    
    /**
     * ogrenciEkle
     * add ogrenci for selected kurs
     * @param  mixed $id
     * @return void
     */
    public function ogrenciEkle(int $id){
        $kurslar = KursModal::where('kursKurumId', Auth::user()->userInstitution)->where('id', $id)->get();
        $ogrenciler = OgrencilerModal::where('kurumId', Auth::user()->userInstitution)->get();
        return view('admin.sertifikalar.add', ['kurslar' => $kurslar, 'ogrenciler' => $ogrenciler, 'action' => 'ogrenciEkle']); 
    }
    
    /**
     * ogrenciListe
     * get list of ogrenci for selected kurs
     * @param  mixed $id
     * @return void
     */
    public function ogrenciListe(int $id){

        $sertifikalar = DB::table('sertifikalar_modals')
        ->join('kurum_modals', 'kurum_modals.id', '=', 'sertifikalar_modals.kurumId')
        ->join('kurs_modals', 'kurs_modals.id', '=', 'sertifikalar_modals.kursId')
        ->join('ogrenciler_modals', 'ogrenciler_modals.id', '=', 'sertifikalar_modals.ogrenciId')
        ->select('sertifikalar_modals.*', 'kurum_modals.kurumAdi','kurs_modals.kursAdi', 'ogrenciler_modals.ogrenciAdi', 'ogrenciler_modals.ogrenciSoyadi')
        ->where('sertifikalar_modals.kurumId', '=', Auth::user()->userInstitution)
        ->where('sertifikalar_modals.kursId', '=', $id)
        ->get();

        return view('admin.sertifikalar.index', ['sertifikalar' => $sertifikalar]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        /* get count of sertifikalar for selected kurumId */
        $sertfikaSayisi = SertifikalarModal::where('kurumId', Auth::user()->userInstitution)->count();
        /* get lisansSayisi from kurum_modals */
        $lisansSayisi = DB::table('kurum_modals')->where('id', Auth::user()->userInstitution)->first()->lisansSayisi;
        /* compare lisansSayisi and sertfikaSayisi */
        if($sertfikaSayisi >= $lisansSayisi){
            return redirect()->route('admin.sertifikalar.index')->with('message', 'Lisans sayısı aşıldı.')->with('message-type', 'error');
        }
        
        /* validation */
        $request->validate([
            'kursBilgisi' => 'required',
            'ogrenciBilgisi' => 'required',
        ]);
        /* validation */

        $sertifikalar = new SertifikalarModal;
        $sertifikalar->kurumId = Auth::user()->userInstitution;
        /* check kursBilgisi belongs to userInstitution */
        $kurs = KursModal::where('id', $request->kursBilgisi)->where('kursKurumId', Auth::user()->userInstitution)->first();
        if(!$kurs){
            return redirect()->route('admin.sertifikalar.index')->with('message', 'Kurs bilgisi bulunamadı.')->with('message-type', 'error');
        }
        $sertifikalar->kursId = $request->kursBilgisi;
        /* check ogrenciBilgisi belongs to userInstitution */
        $ogrenci = OgrencilerModal::where('id', $request->ogrenciBilgisi)->where('kurumId', Auth::user()->userInstitution)->first();
        if(!$ogrenci){
            return redirect()->route('admin.sertifikalar.index')->with('message', 'Öğrenci bilgisi bulunamadı.')->with('message-type', 'error');
        }
        $sertifikalar->ogrenciId = $request->ogrenciBilgisi;
        $sertifikalar->save();

        $belgeOlustur = new SertifikalarController();
        $sertifikalar->sertifikaDosyasi = $belgeOlustur->belgeOlustur($sertifikalar->id, Auth::user()->userInstitution, $request->kursBilgisi, $request->ogrenciBilgisi);

        return redirect()->route('admin.sertifikalar.index')->with('message', 'Sertifika eklendi.')->with('message-type', 'success');;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(int $id)
    {
        //
        $sertifikalar = SertifikalarModal::where('id',$id)->where('kurumId', Auth::user()->userInstitution)->first();
        $kurslar = KursModal::where('kursKurumId', Auth::user()->userInstitution)->get();
        $ogrenciler = OgrencilerModal::where('kurumId', Auth::user()->userInstitution)->get();
        return view('admin.sertifikalar.edit', ['sertifikalar' => $sertifikalar, 'kurslar' => $kurslar, 'ogrenciler' => $ogrenciler]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        //
        /* validation */
        $request->validate([
            'kursBilgisi' => 'required',
            'ogrenciBilgisi' => 'required',
        ]);
        /* validation */

        
        $sertifikalar = SertifikalarModal::where('id', $id)->where('kurumId', Auth::user()->userInstitution)->first();
        $sertifikalar->kurumId = Auth::user()->userInstitution;
        /* check kursBilgisi belongs to userInstitution */
        $kurs = KursModal::where('id', $request->kursBilgisi)->where('kursKurumId', Auth::user()->userInstitution)->first();
        if(!$kurs){
            return redirect()->route('admin.sertifikalar.index')->with('message', 'Kurs bilgisi bulunamadı.')->with('message-type', 'error');
        }
        $sertifikalar->kursId = $request->kursBilgisi;
        /* check ogrenciBilgisi belongs to userInstitution */
        $ogrenci = OgrencilerModal::where('id', $request->ogrenciBilgisi)->where('kurumId', Auth::user()->userInstitution)->first();
        if(!$ogrenci){
            return redirect()->route('admin.sertifikalar.index')->with('message', 'Öğrenci bilgisi bulunamadı.')->with('message-type', 'error');
        }
        $sertifikalar->ogrenciId = $request->ogrenciBilgisi;
        $sertifikalar->save();
        $belgeOlustur = new SertifikalarController();
        $sertifikalar->sertifikaDosyasi = $belgeOlustur->belgeOlustur($sertifikalar->id, Auth::user()->userInstitution, $request->kursBilgisi, $request->ogrenciBilgisi);

        return redirect()->route('admin.sertifikalar.index')->with('message', 'Sertifika güncellendi.')->with('message-type', 'success');;
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        //
        try{
            $sertifikalar = SertifikalarModal::where('id', $id)->where('kurumId', Auth::user()->userInstitution)->first();
            if($sertifikalar == null)
                throw new Exception("Yetkisiz erişim");
            $sertifikalar->delete();
            return redirect()->route('admin.sertifikalar.index')->with('message', 'Sertifika silindi.')->with('message-type', 'success');
        } catch (Exception $e)
        {
            return redirect()->route('admin.sertifikalar.index')->with('message', 'Sertifika silinemedi.')->with('message-type', 'error');
        }

    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls', // Validate file format if needed
        ]);

        $file = $request->file('file');

        // Import users from the Excel file
        $import = new SertifikalarImport();
        $gelen = Excel::import($import , $file);
        $rowCount = $import->getRowCount();
        $totalCount = $import->getTotalRowCount();
        $eklenemeyenler = $import->getEklenemeyenler();
        $eklenemeyenString = '';
        /* add in to string all value of eklenemeyenler */
        foreach($eklenemeyenler as $eklenemeyen) {
            $eklenemeyenString .= ' '.$eklenemeyen.', ';
        }
        if($eklenemeyenString != '')
            $eklenemeyenString = $eklenemeyenString.' TC Kimlik Numaralı öğrenciler aktarılamadı.' ;
        return redirect()->route('admin.sertifikalar.index')->with('message', 'Öğrenciler Excel Dosyasından Başarılı Bir Şekilde Alındı. Toplam '.$totalCount.' kaydın '.$rowCount.' adedi aktarıldı.'.$eklenemeyenString );
    }



}
