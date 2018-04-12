<?php
include 'lib/config.php';
include 'lib/Database.php';
$db = new Database();
?>
<?php include 'inc/header.php';?>
<?php
  if (isset($_GET['del'])) {
   $id = $_GET['del'];

   $getquery = "select * from tbl_image where id='$id'";
   $getImg = $db->select($getquery);
   if ($getImg) {
    while ($imgdata = $getImg->fetch_assoc()) {
    $delimg = $imgdata['image'];
    unlink($delimg);
    }
   }
   
   $query = "delete from tbl_image where id='$id'";
   $delImage = $db->delete($query);
   if ($delImage) {
     echo "<span class='success'>Image Deleted Successfully.
     </span>";
    }else {
     echo "<span class='error'>Image Not Deleted !</span>";
    }
   }
  ?>
 <div class="myform">
  <form action="" method="post" enctype="multipart/form-data">
   <?php
     if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $permited  = array('jpg', 'jpeg', 'png', 'gif');
      $file_name = $_FILES['image']['name'];
      $file_size = $_FILES['image']['size'];
      $file_temp = $_FILES['image']['tmp_name'];

      $div = explode('.', $file_name);
      $file_ext = strtolower(end($div));
      $unique_image = substr(md5(time()), 0, 10).'.'.$file_ext;
      $uploaded_image = "uploads/".$unique_image;

      if (empty($file_name)) {
       echo "<span class='error'>Please Select any Image !</span>";
      }elseif ($file_size >1048567) {
       echo "<span class='error'>Image Size should be less then 1MB!
       </span>";
      } elseif (in_array($file_ext, $permited) === false) {
       echo "<span class='error'>You can upload only:-"
       .implode(', ', $permited)."</span>";
      } else{
      move_uploaded_file($file_temp, $uploaded_image);
      $query = "INSERT INTO tbl_image(image) 
      VALUES('$uploaded_image')";
      $inserted_rows = $db->insert($query);
      if ($inserted_rows) {
       echo "<span class='success'>Image Inserted Successfully.
       </span>";
      }else {
       echo "<span class='error'>Image Not Inserted !</span>";
      }
      }
     }
  ?>
   <table>
    <tr>
     <td>Select Image</td>
     <td><input type="file" name="image"/></td>
    </tr>
    <tr>
     <td></td>
     <td><input type="submit" name="submit" value="Upload"/></td>
    </tr>
   </table>
  </form>
<table>
  <tr>
    <th>#</th>
    <th>Image</th>
    <th>Action</th>
  </tr>
  
  <?php
   $query = "select * from tbl_image";
   $getImage = $db->select($query);
   if ($getImage) {
    $i=0;
    while ($result = $getImage->fetch_assoc()) {
    $i++;
    ?>

   <tr>
    <td><?php echo $i; ?></td>
    <td><img src="<?php echo $result['image']; ?>" height="40px" width="50px"/></td>
    <td><a href="?del=<?php echo $result['id']; ?>">Delete</a></td>
   </tr>
  <?php } } ?>
</table>
 </div>
<?php include 'inc/footer.php';?>
