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
    $emailAddress = 'info@jas-associates.com'; // Full email address
    $emailPassword = 'Info@2021#';        // Email password
    $domainURL = 'jas-associates.com';            // Your websites domain
    $useHTTPS = true;                       // Depending on how your cpanel is set up, you may be using a secure connection and you may not be. Change this from true to false as needed for your situation

    $conn = imap_open('{'.$domainURL.':143/notls}INBOX',$emailAddress,$emailPassword) or die('Cannot connect to domain:' . imap_last_error());
    //$mails = imap_search($conn, 'UNSEEN');
    $mails = imap_search($conn, 'SUBJECT "Comment"');

    if(empty($mails))
        $nMsgCount = 0;
    else
        $nMsgCount = count($mails);


    // $mails = imap_search($conn, 'SUBJECT "Comment"');


    echo('<p>You have '.$nMsgCount.' unread messages.</p>');

    /* END MESSAGE COUNT CODE */

    if ($mails) {

        /* Mail output variable starts*/
        $mailOutput = '';
        $mailOutput.= '<table><tr><th>Subject </th><th> From </th>
						<th> Date Time </th> <th> Content </th></tr>';

        /* rsort is used to display the latest emails on top */
        rsort($mails);

        /* For each email */
        foreach ($mails as $email_number) {

            /* Retrieve specific email information*/
            $headers = imap_fetch_overview($conn, $email_number, 0);

            /* Returns a particular section of the body*/
            $message = imap_fetchbody($conn, $email_number, '1');
            $subMessage = substr($message, 0, 150);
            $finalMessage = trim(quoted_printable_decode($subMessage));


            $mailOutput.= '<div class="row">';

            /* Gmail MAILS header information */
            $mailOutput.= '<td><span class="columnClass">' .
                $headers[0]->subject . '</span></td> ';
            $mailOutput.= '<td><span class="columnClass">' .
                $headers[0]->from . '</span></td>';
            $mailOutput.= '<td><span class="columnClass">' .
                $headers[0]->date . '</span></td>';
            $mailOutput.= '</div>';

            /* Mail body is returned */
            $mailOutput.= '<td><span class="column">' .
                $finalMessage . '</span></td></tr></div>';
        }// End foreach
        $mailOutput.= '</table>';
        echo $mailOutput;
    }//endif
    ?>
</div>
</body>

</html>
<?php
