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
    set_time_limit(500); //
    $emailAddress = 'info@royalsoftsolutions.com'; // Full email address
    $emailPassword = 'Sachin@55#';        // Email password
    $domainURL = 'royalsoftsolutions.com';              // Your websites domain
    $useHTTPS = true;                       // Depending on how your cpanel is set up, you may be using a secure connection and you may not be. Change this from true to false as needed for your situation

    $conn = imap_open('{'.$domainURL.':143/notls}INBOX',$emailAddress,$emailPassword) or die('Cannot connect to domain:' . imap_last_error());
    //$mails = imap_search($conn, 'UNSEEN');
    $mails = imap_search($conn, 'UNSEEN');

    if(empty($mails))
        $nMsgCount = 0;
    else
        $nMsgCount = count($mails);


    // $mails = imap_search($conn, 'SUBJECT "Comment"');


    echo('<p>You have '.$nMsgCount.' unread messages.</p>');

    /* END MESSAGE COUNT CODE */

    if($emails) {

        $count = 1;

        /* put the newest emails on top */
        rsort($emails);

        /* for every email... */
        foreach($emails as $email_number)
        {

            /* get information specific to this email */
            $overview = imap_fetch_overview($emails,$email_number,0);

            $message = imap_fetchbody($emails,$email_number,2);

            /* get mail structure */
            $structure = imap_fetchstructure($emails, $email_number);

            $attachments = array();

            /* if any attachments found... */
            if(isset($structure->parts) && count($structure->parts))
            {
                for($i = 0; $i < count($structure->parts); $i++)
                {
                    $attachments[$i] = array(
                        'is_attachment' => false,
                        'filename' => '',
                        'name' => '',
                        'attachment' => ''
                    );

                    if($structure->parts[$i]->ifdparameters)
                    {
                        foreach($structure->parts[$i]->dparameters as $object)
                        {
                            if(strtolower($object->attribute) == 'filename')
                            {
                                $attachments[$i]['is_attachment'] = true;
                                $attachments[$i]['filename'] = $object->value;
                            }
                        }
                    }

                    if($structure->parts[$i]->ifparameters)
                    {
                        foreach($structure->parts[$i]->parameters as $object)
                        {
                            if(strtolower($object->attribute) == 'name')
                            {
                                $attachments[$i]['is_attachment'] = true;
                                $attachments[$i]['name'] = $object->value;
                            }
                        }
                    }

                    if($attachments[$i]['is_attachment'])
                    {
                        $attachments[$i]['attachment'] = imap_fetchbody($emails, $email_number, $i+1);

                        /* 3 = BASE64 encoding */
                        if($structure->parts[$i]->encoding == 3)
                        {
                            $attachments[$i]['attachment'] = base64_decode($attachments[$i]['attachment']);
                        }
                        /* 4 = QUOTED-PRINTABLE encoding */
                        elseif($structure->parts[$i]->encoding == 4)
                        {
                            $attachments[$i]['attachment'] = quoted_printable_decode($attachments[$i]['attachment']);
                        }
                    }
                }
            }

            /* iterate through each attachment and save it */
            foreach($attachments as $attachment)
            {
                if($attachment['is_attachment'] == 1)
                {
                    $filename = $attachment['name'];
                    if(empty($filename)) $filename = $attachment['filename'];

                    if(empty($filename)) $filename = time() . ".dat";
                    $folder = "attachment";
                    if(!is_dir($folder))
                    {
                        mkdir($folder);
                    }
                    $fp = fopen("./". $folder ."/". $email_number . "-" . $filename, "w+");
                    fwrite($fp, $attachment['attachment']);
                    fclose($fp);
                }
            }
        }
    }

    /* close the connection */
    imap_close($inbox);

    ?>
</div>
</body>

</html>
<?php
