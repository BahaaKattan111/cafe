<?php
# create a table row ( .tr ):
function create_row($row,$section_name) {
    $ID = $row["id"];
    unset( $row["id"] );
    # create a table row ( .tr ):
    echo "<li class='tr-table'>";

    foreach ($row as $value) {
        $ext = pathinfo('../'.$value, PATHINFO_EXTENSION);

        if (in_array($ext, ['gif','jpg','png','svg'])){
?>
        <div class="tr">
            <img src=" <?php echo '../'.$value ;?>" alt="">

        </div>
<?php
        }else{
            echo "<div class='tr'>". $value ." </div>";
        };
    };
   ?>
        <div  class='td-actions'>
            
            <a class='delete-btn' href="<?php echo '../functions/delete.php?id=' . $ID . '&section=' . $section_name; ?>">Delete</a>
            <a  class=' update-btn' href="../update/update-<?php echo $section_name.'.php?id='. $ID;?>">Update</a>
        </div>
    </li>
<?php
};
# search as using multi-inputs:
function SEARCH($conn, $cols, $table_name, $section_name){
    
    $conditions =[];
    $params =[];
    $types ='';
    # avoid repeating "$sql" "bind_params" values 
    #(e.g. type='sss' params='$phone ...', $sql '... LIKE ? ')
    # no need to use 'id' column ! to avoid notices and warnings
    
    foreach($cols as $col){
        if (!empty($_GET[$col])){
            $conditions[$col] =  $col.' LIKE ?';
            $params[$col] = '%'.$_GET[$col].'%';
            $types.= 's';
        };
    };
    $joined_cols = implode(', ',$cols);

    # fetch daata:
    if (count($conditions) > 0) {
        $sql = "SELECT $joined_cols FROM $table_name WHERE".' '. implode( ' AND ',$conditions);
        $stmt = $conn->prepare($sql);
        $joined_values = implode(',',$params);
        $stmt->bind_param( $types, $joined_values);
        $stmt->execute();
        
        if ($stmt){

            $res = $stmt->get_result();
            if ($res-> num_rows > 0) {
                echo "<h5 class='comment-success'>Found".' '. $res-> num_rows .'</h5>';
                while($row = $res->fetch_assoc()){  
                    create_row($row, $section_name);
                };

            }else{
                echo "<h5 class='comment-failed'>Not Found </h5>";
                
                $sql = "SELECT $joined_cols FROM $table_name";
                
                $stmt = $conn->prepare($sql);
                $stmt->execute();

                $res = $stmt->get_result();
                while($row = $res->fetch_assoc()){
                            echo create_row($row, $section_name);
                };
            };
        };
    
    }else{
        $sql = "SELECT $joined_cols FROM $table_name";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        $res = $stmt->get_result();
        while($row = $res->fetch_assoc()){
                echo create_row($row,$section_name);
        };        
    };
};
?>