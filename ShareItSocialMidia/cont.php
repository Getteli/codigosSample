<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
<p class="output"></p>
</body>

<script>
$.ajax({
        url: 'https://graph.facebook.com/v2.10/me?fields=fan_count&access_token=EAACEdEose0cBAPHZAlFs9tBA2izTB6Yc5d4r96Magz2QWcRcHDMtoUC1zuUlVD9tIBZCzCD9mp5gsF2n3XZBETZCIfNlCejIOrrh5ff0c9wJRZBex7E89ecIahvyWdet0vOCli3YZBlAnF6UYtleAO21Lz0FnZAzeGripZAohzoJFLKMujQeRy61m34JgOSoOsG7catvdcxZA9AZDZD',
        type: 'GET',
        dataType: "json",
        success: displayAll
    });

function displayAll(data){
    alert(data);
}
</script>

</html>

<?php


$json = file_get_contents('https://graph.facebook.com/v2.10/me?fields=fan_count&access_token=EAACEdEose0cBAPHZAlFs9tBA2izTB6Yc5d4r96Magz2QWcRcHDMtoUC1zuUlVD9tIBZCzCD9mp5gsF2n3XZBETZCIfNlCejIOrrh5ff0c9wJRZBex7E89ecIahvyWdet0vOCli3YZBlAnF6UYtleAO21Lz0FnZAzeGripZAohzoJFLKMujQeRy61m34JgOSoOsG7catvdcxZA9AZDZD');
$obj = json_decode($json);
echo $obj->fan_count;



?>