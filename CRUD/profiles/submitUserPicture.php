<?php require_once('../../Connections/cboxConn.php'); ?>
<?php
session_start();

$mysqli = new mysqli($hostname_cboxConn, $username_cboxConn, $password_cboxConn, $database_cboxConn);
if (mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}

$user_id = "-1";
if (isset($_SESSION['MM_UserID'])) {
  $user_id = $_SESSION['MM_UserID'];
}

$box_id = "";
$query_getBoxID = "select box_id
 from athletes
WHERE user_id = $user_id";

if ($result = $mysqli->query($query_getBoxID)) {
	$row = $result->fetch_assoc();
	$box_id = $row['box_id'];
}

///picture
if(isset($_FILES['upload'])) {
	echo $_FILES['upload']['name']."\n";
	echo $_FILES['upload']['tmp_name']."\n";
    $file = $_FILES['upload']['tmp_name'];
    $allowedMimes = array('image/png', 'image/jpg', 'image/pjpeg', 'image/jpeg');


	$storagePath = '/var/www/images/profiles/'.$box_id.'/';
	$completePath = $storagePath . $_FILES['upload']['name'];
	
    $fileName = upload($file, $storagePath, $allowedMimes);
    if (!$fileName) {
        exit ('Your file type is not allowed.');
    } else {
         // check if file is image, optional, in case you allow multiple types of files.
         // $imageInfo = @getimagesize($storagePath.'/'.$fileName);
        //exit ("Your uploaded file is {$fileName} and can be found at $completePath");
		echo $_FILES['upload']['name']. ", $completePath\n";
		if(move_uploaded_file($_FILES['upload']['tmp_name'], $completePath)) 
		{
			//echo "Uploaded!";
			$stmt = $mysqli->prepare("update athletes set picture_url=? where user_id=?");
			$stmt->bind_param( 'ss', substr($completePath, 8), $user_id);

			if($result = $stmt->execute()) {
				echo "1";
				$stmt->close();
			} else {
				echo "0";
			}
		}else{
			echo "Server Error!";
		} 
    }
} else {
	echo "HOUSTON!!! HOUSTON!!!! ";
}

$mysqli->close();

function getExtensionToMimeTypeMapping() {
    return array(
        'ai'=>'application/postscript',
        'aif'=>'audio/x-aiff',
        'aifc'=>'audio/x-aiff',
        'aiff'=>'audio/x-aiff',
        'anx'=>'application/annodex',
        'asc'=>'text/plain',
        'au'=>'audio/basic',
        'avi'=>'video/x-msvideo',
        'axa'=>'audio/annodex',
        'axv'=>'video/annodex',
        'bcpio'=>'application/x-bcpio',
        'bin'=>'application/octet-stream',
        'bmp'=>'image/bmp',
        'c'=>'text/plain',
        'cc'=>'text/plain',
        'ccad'=>'application/clariscad',
        'cdf'=>'application/x-netcdf',
        'class'=>'application/octet-stream',
        'cpio'=>'application/x-cpio',
        'cpt'=>'application/mac-compactpro',
        'csh'=>'application/x-csh',
        'css'=>'text/css',
        'csv'=>'text/csv',
        'dcr'=>'application/x-director',
        'dir'=>'application/x-director',
        'dms'=>'application/octet-stream',
        'doc'=>'application/msword',
        'drw'=>'application/drafting',
        'dvi'=>'application/x-dvi',
        'dwg'=>'application/acad',
        'dxf'=>'application/dxf',
        'dxr'=>'application/x-director',
        'eps'=>'application/postscript',
        'etx'=>'text/x-setext',
        'exe'=>'application/octet-stream',
        'ez'=>'application/andrew-inset',
        'f'=>'text/plain',
        'f90'=>'text/plain',
        'flac'=>'audio/flac',
        'fli'=>'video/x-fli',
        'flv'=>'video/x-flv',
        'gif'=>'image/gif',
        'gtar'=>'application/x-gtar',
        'gz'=>'application/x-gzip',
        'h'=>'text/plain',
        'hdf'=>'application/x-hdf',
        'hh'=>'text/plain',
        'hqx'=>'application/mac-binhex40',
        'htm'=>'text/html',
        'html'=>'text/html',
        'ice'=>'x-conference/x-cooltalk',
        'ief'=>'image/ief',
        'iges'=>'model/iges',
        'igs'=>'model/iges',
        'ips'=>'application/x-ipscript',
        'ipx'=>'application/x-ipix',
        'jpe'=>'image/jpeg',
        'jpeg'=>'image/jpeg',
        'jpg'=>'image/jpeg',
        'js'=>'application/x-javascript',
        'kar'=>'audio/midi',
        'latex'=>'application/x-latex',
        'lha'=>'application/octet-stream',
        'lsp'=>'application/x-lisp',
        'lzh'=>'application/octet-stream',
        'm'=>'text/plain',
        'man'=>'application/x-troff-man',
        'me'=>'application/x-troff-me',
        'mesh'=>'model/mesh',
        'mid'=>'audio/midi',
        'midi'=>'audio/midi',
        'mif'=>'application/vnd.mif',
        'mime'=>'www/mime',
        'mov'=>'video/quicktime',
        'movie'=>'video/x-sgi-movie',
        'mp2'=>'audio/mpeg',
        'mp3'=>'audio/mpeg',
        'mpe'=>'video/mpeg',
        'mpeg'=>'video/mpeg',
        'mpg'=>'video/mpeg',
        'mpga'=>'audio/mpeg',
        'ms'=>'application/x-troff-ms',
        'msh'=>'model/mesh',
        'nc'=>'application/x-netcdf',
        'oga'=>'audio/ogg',
        'ogg'=>'audio/ogg',
        'ogv'=>'video/ogg',
        'ogx'=>'application/ogg',
        'oda'=>'application/oda',
        'pbm'=>'image/x-portable-bitmap',
        'pdb'=>'chemical/x-pdb',
        'pdf'=>'application/pdf',
        'pgm'=>'image/x-portable-graymap',
        'pgn'=>'application/x-chess-pgn',
        'png'=>'image/png',
        'pnm'=>'image/x-portable-anymap',
        'pot'=>'application/mspowerpoint',
        'ppm'=>'image/x-portable-pixmap',
        'pps'=>'application/mspowerpoint',
        'ppt'=>'application/mspowerpoint',
        'ppz'=>'application/mspowerpoint',
        'pre'=>'application/x-freelance',
        'prt'=>'application/pro_eng',
        'ps'=>'application/postscript',
        'qt'=>'video/quicktime',
        'ra'=>'audio/x-realaudio',
        'ram'=>'audio/x-pn-realaudio',
        'ras'=>'image/cmu-raster',
        'rgb'=>'image/x-rgb',
        'rm'=>'audio/x-pn-realaudio',
        'roff'=>'application/x-troff',
        'rpm'=>'audio/x-pn-realaudio-plugin',
        'rtf'=>'text/rtf',
        'rtx'=>'text/richtext',
        'scm'=>'application/x-lotusscreencam',
        'set'=>'application/set',
        'sgm'=>'text/sgml',
        'sgml'=>'text/sgml',
        'sh'=>'application/x-sh',
        'shar'=>'application/x-shar',
        'silo'=>'model/mesh',
        'sit'=>'application/x-stuffit',
        'skd'=>'application/x-koan',
        'skm'=>'application/x-koan',
        'skp'=>'application/x-koan',
        'skt'=>'application/x-koan',
        'smi'=>'application/smil',
        'smil'=>'application/smil',
        'snd'=>'audio/basic',
        'sol'=>'application/solids',
        'spl'=>'application/x-futuresplash',
        'spx'=>'audio/ogg',
        'src'=>'application/x-wais-source',
        'step'=>'application/STEP',
        'stl'=>'application/SLA',
        'stp'=>'application/STEP',
        'sv4cpio'=>'application/x-sv4cpio',
        'sv4crc'=>'application/x-sv4crc',
        'swf'=>'application/x-shockwave-flash',
        't'=>'application/x-troff',
        'tar'=>'application/x-tar',
        'tcl'=>'application/x-tcl',
        'tex'=>'application/x-tex',
        'texi'=>'application/x-texinfo',
        'texinfo'=>'application/x-texinfo',
        'tif'=>'image/tiff',
        'tiff'=>'image/tiff',
        'tr'=>'application/x-troff',
        'tsi'=>'audio/TSP-audio',
        'tsp'=>'application/dsptype',
        'tsv'=>'text/tab-separated-values',
        'txt'=>'text/plain',
        'unv'=>'application/i-deas',
        'ustar'=>'application/x-ustar',
        'vcd'=>'application/x-cdlink',
        'vda'=>'application/vda',
        'viv'=>'video/vnd.vivo',
        'vivo'=>'video/vnd.vivo',
        'vrml'=>'model/vrml',
        'wav'=>'audio/x-wav',
        'wrl'=>'model/vrml',
        'xbm'=>'image/x-xbitmap',
        'xlc'=>'application/vnd.ms-excel',
        'xll'=>'application/vnd.ms-excel',
        'xlm'=>'application/vnd.ms-excel',
        'xls'=>'application/vnd.ms-excel',
        'xlw'=>'application/vnd.ms-excel',
        'xml'=>'application/xml',
        'xpm'=>'image/x-xpixmap',
        'xspf'=>'application/xspf+xml',
        'xwd'=>'image/x-xwindowdump',
        'xyz'=>'chemical/x-pdb',
        'zip'=>'application/zip',
    );
}

function getMimeType($filePath) {

    if (!is_file($filePath)) {
        return false;
    }

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $filePath);
    finfo_close($finfo);

    return $mime;
}

function upload($filePath, $destinationDir = 'images/profiles/', array $allowedMimes = array()) {
	echo "UPLOAD\n".$filePath . "\n".$destinationDir."\n";
    if (!is_file($filePath) || !is_dir($destinationDir)) {
		echo "First IF in upload\n";
        return false;
    }

    if (!($mime = getMimeType($filePath))) {
		echo "Second IF in upload\n";
        return false;
    }
	echo $mime."\n";
    if (!in_array($mime, $allowedMimes)) {
		echo "Third IF in upload\n";
        return false;
    }

    $ext = null;
    $extMapping = getExtensionToMimeTypeMapping();
    foreach ($extMapping as $extension => $mimeType) {
        if ($mimeType == $mime) {
			echo "$mimeType == $mime, thus ext = $extension \n";
            $ext = $extension;
            break;
        }
    }

    if (empty($ext)) {
		echo "FOURTH IF";
        $ext = pathinfo($filePath, PATHINFO_EXTENSION);
    }

    if (empty($ext)) {
		echo "FIFTH IF";
        return false;
    }

    $fileName = md5(uniqid(rand(0, time()), true)) . '.' . $ext;
    $newFilePath = $destinationDir . $fileName;
	echo "$fileName ************ $filePath ********* $newFilePath\n";
    /*if(!rename($filePath, $newFilePath)) {
		echo "$filePath ********* $newFilePath";
        return false;
    }*/

    return $fileName;
}

?>