<?php include_once(__DIR__."/../../config.php");

$page = $_POST['pageID'];

$i = 0;

$gq = "SELECT *
    FROM `page_uploads`
    WHERE `page`='$page'";
$gresult = mysqli_query($conn, $gq);
$gcount = mysqli_num_rows($gresult);
if ($gcount > 0) {
    while ($item = mysqli_fetch_assoc($gresult)) {
        $pu_id = $item['id'];
        $pu_fileName = $item['fileName'];
        $pu_loc = $item['location'];
        $pu_user = $item['user'];
        $pu_file = $pu_loc.$pu_fileName;
        if ($pu_user == $id) {
            $del_acccess = true;
        } else {
            $del_acccess = false;
        }
        if (isset($pu_file)) {
            $fileParts = explode('.',$pu_file);
            $fileType = end($fileParts);

            if ($fileType == "txt") {
                $file_img = "txt.png";
                $file_txt = $pu_file;
            } else
            if ($fileType == "zip") {
                $file_img = "zip.png";
                $file_dl = $pu_file;
            } else {
                $file_img = $pu_file;
            }
        }
        
        $cPageMembers = "SELECT *
            FROM `page_members`
            WHERE `page`='$page'
            AND `user`='$pu_user'";
        $cPMResult = mysqli_query($conn, $cPageMembers);
        $countPM = mysqli_num_rows($cPMResult);
        if ($countPM == 1) {
            $pmRow = mysqli_fetch_assoc($cPMResult);
            $page_rank = $pmRow['rank'];
            $page_status = $pmRow['status'];
            
            $gallery = array(
                "responseCode"=>"1",
                "file_id"=>"$pu_id",
                "file"=>"$file_img",
                "delete_access"=>"$del_acccess",
                "page_rank"=>"$page_rank"
            );

            $data[$i] = $gallery;
            $i++;
        }
    }
}
echo json_encode($data);
?>