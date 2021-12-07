
<?php
   function createconnection()
   {
     $host = 'localhost';
     $user = 'root';
     $pass = 'passkey';
     $db   = 'Doctor';
     //create connection
     $con  = mysqli_connect($host, $user, $pass, $db);
     return $con;
   }

   function machines()
   {
     $con   = createconnection();
     $query = "SELECT	H.HostIP, M.Hostname, V.Packagename, V.VersionNum, H.UpdateTime, V.Createtime, V.Deprecate\n"
       . "FROM Machinehaspackage as H, Version as V, Machine as M\n"
       . "WHERE	V.VersionID = H.VersionID AND H.HostIP = M.HostIP\n"
       . "ORDER BY H.HostIP, V.Packagename";
     $sql   = mysqli_query($con, $query);
     $data  = array();
     while ($row = mysqli_fetch_assoc($sql)) { // row will be an array with Number and checked as items
       $data[] = $row;
     }
     return $data;
   }

   function packages($name)
   {
     $con   = createconnection();
     $query = "SELECT `VersionNum`,`Deprecate`,`Createtime` FROM `Version`\n"
       . "WHERE Version.PackageName='".$name."'\n"
       . "ORDER BY VersionNum";
     $sql   = mysqli_query($con, $query);
     $data  = array();
     while ($row = mysqli_fetch_assoc($sql)) { // row will be an array with Number and checked as items
       $data[] = $row;
     }
     return $data;
   }

     function html($data = array())
     {
         $rows = array();
         foreach ($data as $row) {
             $cells = array();
             foreach ($row as $cell) {
                 $cells[] = "<td>{$cell}</td>";
             }

             $rows[] = "<tr>" . implode('', $cells) . "</tr>";
         }
         return implode('', $rows);
     }

     function mahtml($data = array())
     {
         $rows = array();
         foreach ($data as $row) {
             $cells = array();
             $first = true;
             foreach ($row as $cell) {
               if ($first) {
                 $first = false;
                 $cells[] = "<td><a href=\"http://10.2.2.{$cell}\">10.2.2.{$cell}</a></td>";
               }
               else {
                 $cells[] = "<td>{$cell}</td>";
               }

             }

             $rows[] = "<tr>" . implode('', $cells) . "</tr>";
         }
         return implode('', $rows);
     }




   ?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <title>Doctor Deploy</title>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link href="https://stackpath.bootstrapcdn.com/bootswatch/4.1.0/darkly/bootstrap.min.css" rel="stylesheet" integrity="sha384-J01jr7rrJqxij+hUE1E+8N35mlD7L/TMrAO7tOarwMP7AWJM3P/lGXOjt0KLNhtE" crossorigin="anonymous">
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>
   </head>
   <body>
      <div class="container">
         <br>
         <h1>Doctor Deployment</h1>
         <br>
         <div class="card border-primary mb-3">
            <h4 class="card-header">Machine Status</h4>
            <div class="card-body">
               <table class="table table-hover">
                  <thead>
                     <tr>
                        <th>IP</th>
                        <th>Hostname</th>
                        <th>Package</th>
                        <th>Version</th>
                        <th>Pushed</th>
                        <th>Created</th>
                        <th>Depreciate</th>
                     </tr>
                  </thead>
                  <tbody>
                     <?php echo mahtml(machines()); ?>
                  </tbody>
               </table>
            </div>
         </div>
         <h3>Package Versions</h3>
         <div class="row">
       <div class="col-lg-6">
         <div class="card border-primary mb-3">
            <h5 class="card-header">Frontend HTML (feweb)</h5>
            <div class="card-body">
               <table class="table table-hover">
                  <thead>
                     <tr>
                        <th>Version</th>
                        <th>Depreciated</th>
                        <th>Created</th>
                     </tr>
                  </thead>
                  <tbody>
                     <?php echo html(packages('feweb')); ?>
                  </tbody>
               </table>
            </div>
         </div></div><div class="col-lg-6">
         <div class="card border-primary mb-3">
            <h5 class="card-header">Frontend PHP (fephp)</h5>
            <div class="card-body">
               <table class="table table-hover">
                  <thead>
                     <tr>
                        <th>Version</th>
                        <th>Depreciated</th>
                        <th>Created</th>
                     </tr>
                  </thead>
                  <tbody>
                     <?php echo html(packages('fephp')); ?>
                  </tbody>
               </table>
            </div>
         </div></div><div class="col-lg-6">
         <div class="card border-primary mb-3">
            <h5 class="card-header">Database (db)</h5>
            <div class="card-body">
               <table class="table table-hover">
                  <thead>
                     <tr>
                        <th>Version</th>
                        <th>Depreciated</th>
                        <th>Created</th>
                     </tr>
                  </thead>
                  <tbody>
                     <?php echo html(packages('db')); ?>
                  </tbody>
               </table>
            </div>
         </div></div><div class="col-lg-6">
         <div class="card border-primary mb-3">
            <h5 class="card-header">Backend PHP (bephp)</h5>
            <div class="card-body">
               <table class="table table-hover">
                  <thead>
                     <tr>
                        <th>Version</th>
                        <th>Depreciated</th>
                        <th>Created</th>
                     </tr>
                  </thead>
                  <tbody>
                     <?php echo html(packages('bephp')); ?>
                  </tbody>
               </table>
            </div>
   </body>
</html>
