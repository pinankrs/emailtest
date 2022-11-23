<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href=
    "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="style.css">

    <script>
        function getEmails() {
            document.getElementById('dataDivID')
                .style.display = "block";
        }
    </script>
    <style>
        body {
            font-family: Arial;
        }
        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }
        tr:nth-child(even) {
            background-color: #dddddd;
        }
        td, th {
            padding: 8px;
            width:100px;
            border: 1px solid #dddddd;
            text-align: left;
        }
        .form-container {
            padding: 20px;
            background: #F0F0F0;
            border: #e0dfdf 1px solid;
            border-radius: 2px;
        }
        * {
            box-sizing: border-box;
        }

        .columnClass {
            float: left;
            padding: 10px;
        }

        .row:after {
            content: "";
            display: table;
            clear: both;
        }

        .btn {
            background: #333;
            border: #1d1d1d 1px solid;
            color: #f0f0f0;
            font-size: 0.9em;
            width: 200px;
            border-radius: 2px;
            background-color: #f1f1f1;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #ddd;
        }

        .btn.active {
            background-color: #666;
            color: white;
        }

    </style>

</head>

<body>
<h2>List Emails from Gmail using PHP and IMAP</h2>

<div id="btnContainer">
    <button class="btn active" onclick="getEmails()">
        <i class="fa fa-bars"></i>Click to get gmail mails
    </button>
</div>
<br>

<div id="dataDivID" class="form-container" style="display:none;">
    <?php
    $emailAddress = 'info@royalsoftsolutions.com'; // Full email address
    $emailPassword = 'Sachin@55#';        // Email password
    $domainURL = 'royalsoftsolutions.com';              // Your websites domain
    $useHTTPS = true;                       // Depending on how your cpanel is set up, you may be using a secure connection and you may not be. Change this from true to false as needed for your situation

    $conn = imap_open('{'.$domainURL.':143/notls}',$emailAddress,$emailPassword) or die('Cannot connect to domain:' . imap_last_error());
    $conn1 = imap_open('{'.$domainURL.':143/notls}Sent',$emailAddress,$emailPassword) or die('Cannot connect to domain:' . imap_last_error());
    //$mails = imap_search($conn, 'UNSEEN');
    $mails = imap_search($mailboxes, 'SUBJECT "Comment"');
    $mailboxes = imap_list($conn1, '{'.$domainURL.':143/notls}', '*');


    echo "<pre>";
    print_r($mailboxes);
    if(empty($mails))
        $nMsgCount = 0;
    else
        $nMsgCount = count($mails);


   // $mails = imap_search($conn, 'SUBJECT "Comment"');


    echo('<p>You have '.$nMsgCount.' unread messages.</p>');

    /* END MESSAGE COUNT CODE */

    ?>
</div>
</body>

</html>
