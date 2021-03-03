<?php
header('contact-type:text/javascript;charset=utf-8');
echo "<select onchange='myFunction(this.value)'>";
echo "<option value=''>select your number</option>";
echo "<option value='5'>5</option>";
echo "<option value='10'>10</option>";
echo "<option value='15'>15</option>";
echo "</select>";
?>
<script>
    function myFunction($numbers){
        for($count=0;$count<$numbers;$count++)
            echo $count;
    }
</script>