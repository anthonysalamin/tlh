<?php

  /*
    * Custom form action v.5
    * ⚠️ REPLACE API KEYS 👻
    * TO DO: maybe use sendgrid SMTP ?
    * - send email to client via Gmail SMTP
    * - update list via Mailchimp API
    * - update Sheet via Google Sheet API
    * Build date: 18.06.2020 | anthonysalamin.ch
  */

  // allow script to be acessed from another domain
  header('Access-Control-Allow-Origin: https://www.tlh-sierre.ch');
  header('Set-Cookie: cross-site-cookie=name; SameSite=None; Secure');

  /*
  * PHPmailer
  * extract data + populate HTML body
  */

  // Import PHPMailer classes into the global namespace
  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\SMTP;
  use PHPMailer\PHPMailer\Exception;

  // load the PHPmailer class files directly
  require '/home/tlhspauf/public_html/PHPMailer-master/src/Exception.php';
  require '/home/tlhspauf/public_html//PHPMailer-master/src/PHPMailer.php';
  require '/home/tlhspauf/public_html/PHPMailer-master/src/SMTP.php';

    $mail = new PHPMailer(true);
    try {

        $from = 'tlh.sierre.confirmation@gmail.com';
        $to = $_POST['EMAIL'];    
        $firstName = ucwords(strtolower(htmlspecialchars($_POST['FNAME'])));
        $lastName = ucwords(strtolower(htmlspecialchars($_POST['LNAME'])));
        $eventName = $_POST['EVENTNAME'] ?? '';
        $assistance = htmlspecialchars($_POST['Message']);
        $inputs = $_POST;
        $urlEmailCover = "https://uploads-ssl.webflow.com/5abe088eed72f876bf4b2347/5f4e06332a37bbd6fb829687_cover-email-confirmation.jpg";

        // handle empty message
        if (empty($assistance)) {
            $assistance = "Aucun message particulier";
        }
                
        // handle timezone stamp
        date_default_timezone_set("Europe/Berlin");
        $date = date('d.m.Y');
        $hour = date('H:i');
        $year = date('Y');
        
        // CSS colors
        $green = "#3bbd64";
        $singular = "billet";
        $plural = "billets";

        // handle greetings
        $greetings = '';
        if ($hour < 18) {
          $greetings .= "Bonjour";    
        } else {
          $greetings .= "Bonsoir";
        }
        
        // handle reservations formating
        $reservations = '';
        $sum = 0;
        foreach ($inputs as $key => $value) {
            if (strpos($key, 'à') !== false) {

                // handle key formating
                $key = str_replace("_"," ","$key");

                // handle billet(s) plural
                $ticketCount = '';
                if ($value == 0) {
                    continue;
                    } elseif ($value == 1) {
                    $ticketCount .= "$value $singular";
                    } else {
                    $ticketCount .= "$value $plural";
                }
                  
                // inject formating into variable for HTML body
                $reservations .= '
                  <div style="background-color:#edddff;">
                  <div class="block-grid mixed-two-up no-stack" style="Margin: 0 auto; min-width: 320px; max-width: 500px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: #f0ffed;">
                    <div style="border-collapse: collapse;display: table;width: 100%;background-color:#f0ffed;">
                      <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#edddff;"><tr><td align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:500px"><tr class="layout-full-width" style="background-color:#f0ffed"><![endif]-->
                      <!--[if (mso)|(IE)]><td align="center" width="375" style="background-color:#f0ffed;width:375px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 0px; padding-left: 0px; padding-top:0px; padding-bottom:0px;"><![endif]-->
                      <div class="col num9" style="display: table-cell; vertical-align: top; min-width: 320px; max-width: 369px; width: 375px;">
                        <div style="width:100% !important;">
                          <!--[if (!mso)&(!IE)]><!-->
                          <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:0px; padding-bottom:0px; padding-right: 0px; padding-left: 0px;">
                            <!--<![endif]-->
                            <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 0px; padding-left: 20px; padding-top: 10px; padding-bottom: 10px; font-family: Arial, sans-serif"><![endif]-->
                            <div style="color:#0acd34;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;line-height:1.2;padding-top:10px;padding-right:0px;padding-bottom:10px;padding-left:20px;">
                              <div style="font-size: 14px; line-height: 1.2; color: #0acd34; font-family: Arial, Helvetica Neue, Helvetica, sans-serif; mso-line-height-alt: 17px;">
                                <p dir="ltr" style="font-size: 14px; line-height: 1.2; word-break: break-word; mso-line-height-alt: 17px; margin: 0;"><span style="font-size: 14px;">' . $key . '</span></p>
                              </div>
                            </div>
                            <!--[if mso]></td></tr></table><![endif]-->
                            <!--[if (!mso)&(!IE)]><!-->
                          </div>
                          <!--<![endif]-->
                        </div>
                      </div>
                      <!--[if (mso)|(IE)]></td></tr></table><![endif]-->
                      <!--[if (mso)|(IE)]></td><td align="center" width="125" style="background-color:#f0ffed;width:125px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 0px; padding-left: 0px; padding-top:0px; padding-bottom:0px;"><![endif]-->
                      <div class="col num3" style="display: table-cell; vertical-align: top; max-width: 320px; min-width: 123px; width: 125px;">
                        <div style="width:100% !important;">
                          <!--[if (!mso)&(!IE)]><!-->
                          <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:0px; padding-bottom:0px; padding-right: 0px; padding-left: 0px;">
                            <!--<![endif]-->
                            <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 20px; padding-left: 0px; padding-top: 10px; padding-bottom: 10px; font-family: Arial, sans-serif"><![endif]-->
                            <div style="color:#0acd34;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;line-height:1.2;padding-top:10px;padding-right:20px;padding-bottom:10px;padding-left:0px;">
                              <div style="font-size: 14px; line-height: 1.2; color: #0acd34; font-family: Arial, Helvetica Neue, Helvetica, sans-serif; mso-line-height-alt: 17px;">
                                <p style="font-size: 14px; line-height: 1.2; word-break: break-word; text-align: right; mso-line-height-alt: 17px; margin: 0;"><span style="font-size: 14px;">' . $ticketCount . '</span></p>
                              </div>
                            </div>
                            <!--[if mso]></td></tr></table><![endif]-->
                            <!--[if (!mso)&(!IE)]><!-->
                          </div>
                          <!--<![endif]-->
                        </div>
                      </div>
                      <!--[if (mso)|(IE)]></td></tr></table><![endif]-->
                      <!--[if (mso)|(IE)]></td></tr></table></td></tr></table><![endif]-->
                    </div>
                  </div>
                </div>
                ';
            }
        }

        // HTML message
        $message = '
          <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

          <html xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:v="urn:schemas-microsoft-com:vml">

          <head>
            <!--[if gte mso 9]><xml><o:OfficeDocumentSettings><o:AllowPNG/><o:PixelsPerInch>96</o:PixelsPerInch></o:OfficeDocumentSettings></xml><![endif]-->
            <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
            <meta content="width=device-width" name="viewport" />
            <!--[if !mso]><!-->
            <meta content="IE=edge" http-equiv="X-UA-Compatible" />
            <!--<![endif]-->
            <title>TLH Confirmation</title>
            <!--[if !mso]><!-->
            <!--<![endif]-->
            <style type="text/css">
              body{margin:0;padding:0}table,td,tr{vertical-align:top;border-collapse:collapse}*{line-height:inherit}a[x-apple-data-detectors=true]{color:inherit!important;text-decoration:none!important}
            </style>
            <style id="media-query" type="text/css">
              @media (max-width:520px){.block-grid,.col{min-width:320px!important;max-width:100%!important;display:block!important}.block-grid{width:100%!important}.col{width:100%!important}.col>div{margin:0 auto}img.fullwidth,img.fullwidthOnMobile{max-width:100%!important}.no-stack .col{min-width:0!important;display:table-cell!important}.no-stack.two-up .col{width:50%!important}.no-stack .col.num4{width:33%!important}.no-stack .col.num8{width:66%!important}.no-stack .col.num4{width:33%!important}.no-stack .col.num3{width:25%!important}.no-stack .col.num6{width:50%!important}.no-stack .col.num9{width:75%!important}.video-block{max-width:none!important}.mobile_hide{min-height:0;max-height:0;max-width:0;display:none;overflow:hidden;font-size:0}.desktop_hide{display:block!important;max-height:none!important}}
            </style>
          </head>

          <body class="clean-body" style="margin: 0; padding: 0; -webkit-text-size-adjust: 100%; background-color: #FFFFFF;">
            <!--[if IE]><div class="ie-browser"><![endif]-->
            <table bgcolor="#FFFFFF" cellpadding="0" cellspacing="0" class="nl-container" role="presentation" style="table-layout: fixed; vertical-align: top; min-width: 320px; Margin: 0 auto; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #FFFFFF; width: 100%;"
              valign="top" width="100%">
              <tbody>
                <tr style="vertical-align: top;" valign="top">
                  <td style="word-break: break-word; vertical-align: top;" valign="top">
                    <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td align="center" style="background-color:#FFFFFF"><![endif]-->
                    <div style="background-color:#edddff;">
                      <div class="block-grid" style="Margin: 0 auto; min-width: 320px; max-width: 500px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: #ffffff;">
                        <div style="border-collapse: collapse;display: table;width: 100%;background-color:#ffffff;">
                          <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#edddff;"><tr><td align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:500px"><tr class="layout-full-width" style="background-color:#ffffff"><![endif]-->
                          <!--[if (mso)|(IE)]><td align="center" width="500" style="background-color:#ffffff;width:500px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 0px; padding-left: 0px; padding-top:0px; padding-bottom:0px;"><![endif]-->
                          <div class="col num12" style="min-width: 320px; max-width: 500px; display: table-cell; vertical-align: top; width: 500px;">
                            <div style="width:100% !important;">
                              <!--[if (!mso)&(!IE)]><!-->
                              <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:0px; padding-bottom:0px; padding-right: 0px; padding-left: 0px;">
                                <!--<![endif]-->
                                <div align="center" class="img-container center fixedwidth" style="padding-right: 0px;padding-left: 0px;">
                                  <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr style="line-height:0px"><td style="padding-right: 0px;padding-left: 0px;" align="center"><![endif]--><img align="center" alt="Image" border="0" class="center fixedwidth" src="' . $urlEmailCover . '" style="text-decoration: none; -ms-interpolation-mode: bicubic; border: 0; height: auto; width: 100%; max-width: 500px; display: block;"
                                    title="TLH réservation" width="500" />
                                  <!--[if mso]></td></tr></table><![endif]-->
                                </div>
                                <!--[if (!mso)&(!IE)]><!-->
                              </div>
                              <!--<![endif]-->
                            </div>
                          </div>
                          <!--[if (mso)|(IE)]></td></tr></table><![endif]-->
                          <!--[if (mso)|(IE)]></td></tr></table></td></tr></table><![endif]-->
                        </div>
                      </div>
                    </div>
                    <div style="background-color:#edddff;">
                      <div class="block-grid" style="Margin: 0 auto; min-width: 320px; max-width: 500px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: #ffffff;">
                        <div style="border-collapse: collapse;display: table;width: 100%;background-color:#ffffff;">
                          <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#edddff;"><tr><td align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:500px"><tr class="layout-full-width" style="background-color:#ffffff"><![endif]-->
                          <!--[if (mso)|(IE)]><td align="center" width="500" style="background-color:#ffffff;width:500px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 0px; padding-left: 0px; padding-top:0px; padding-bottom:0px;"><![endif]-->
                          <div class="col num12" style="min-width: 320px; max-width: 500px; display: table-cell; vertical-align: top; width: 500px;">
                            <div style="width:100% !important;">
                              <!--[if (!mso)&(!IE)]><!-->
                              <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:0px; padding-bottom:0px; padding-right: 0px; padding-left: 0px;">
                                <!--<![endif]-->
                                <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 20px; padding-left: 20px; padding-top: 40px; padding-bottom: 40px; font-family: Verdana, sans-serif"><![endif]-->
                                <div style="color:#808080;font-family:Verdana, Geneva, sans-serif;line-height:1.5;padding-top:40px;padding-right:20px;padding-bottom:40px;padding-left:20px;">
                                  <div style="font-size: 14px; line-height: 1.5; font-family: Verdana, Geneva, sans-serif; color: #808080; mso-line-height-alt: 21px;">
                                    <p dir="ltr" style="line-height: 1.5; word-break: break-word; text-align: center; font-family: Verdana, Geneva, sans-serif; font-size: 14px; mso-line-height-alt: 21px; margin: 0;"><span style="font-size: 14px;">' . $greetings . ' <span style="color: #333333;"><strong>' . $firstName . '</strong></span>,</span>
                                    </p>
                                    <p dir="ltr" style="line-height: 1.5; word-break: break-word; text-align: center; font-family: Verdana, Geneva, sans-serif; mso-line-height-alt: NaNpx; margin: 0;">Ci-dessous, votre confirmation <span style="color: #ff00ff;">♥</span><br>Ceci est un mail automatique.<br>Une question ? Appelez-nous au<br>027 452 02 97</p><br>
                                    <p dir="ltr" style="line-height: 1.5; word-break: break-word; text-align: center; font-family: Verdana, Geneva, sans-serif; font-size: 14px; mso-line-height-alt: 21px; margin: 0;"><span style="font-size: 14px;">Le TLH-Sierre</span></p>
                                  </div>
                                </div>
                                <!--[if mso]></td></tr></table><![endif]-->
                                <!--[if (!mso)&(!IE)]><!-->
                              </div>
                              <!--<![endif]-->
                            </div>
                          </div>
                          <!--[if (mso)|(IE)]></td></tr></table><![endif]-->
                          <!--[if (mso)|(IE)]></td></tr></table></td></tr></table><![endif]-->
                        </div>
                      </div>
                    </div>
                    <div style="background-color:#edddff;">
                      <div class="block-grid" style="Margin: 0 auto; min-width: 320px; max-width: 500px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: #f0ffed;">
                        <div style="border-collapse: collapse;display: table;width: 100%;background-color:#f0ffed;">
                          <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#edddff;"><tr><td align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:500px"><tr class="layout-full-width" style="background-color:#f0ffed"><![endif]-->
                          <!--[if (mso)|(IE)]><td align="center" width="500" style="background-color:#f0ffed;width:500px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 20px; padding-left: 20px; padding-top:40px; padding-bottom:10px;"><![endif]-->
                          <div class="col num12" style="min-width: 320px; max-width: 500px; display: table-cell; vertical-align: top; width: 500px;">
                            <div style="width:100% !important;">
                              <!--[if (!mso)&(!IE)]><!-->
                              <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:40px; padding-bottom:10px; padding-right: 20px; padding-left: 20px;">
                                <!--<![endif]-->
                                <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 0px; padding-left: 0px; padding-top: 0px; padding-bottom: 20px; font-family: Arial, sans-serif"><![endif]-->
                                <div style="color:#333333;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;line-height:1.2;padding-top:0px;padding-right:0px;padding-bottom:20px;padding-left:0px;">
                                  <div style="font-size: 14px; line-height: 1.2; color: #333333; font-family: Arial, Helvetica Neue, Helvetica, sans-serif; mso-line-height-alt: 17px;">
                                    <p style="line-height: 1.2; word-break: break-word; text-align: left; mso-line-height-alt: NaNpx; margin: 0; text-transform: uppercase"><strong>' . $eventName . '</strong></p>
                                  </div>
                                </div>
                                <!--[if mso]></td></tr></table><![endif]-->
                                <table border="0" cellpadding="0" cellspacing="0" class="divider" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;"
                                  valign="top" width="100%">
                                  <tbody>
                                    <tr style="vertical-align: top;" valign="top">
                                      <td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 0px; padding-right: 0px; padding-bottom: 0px; padding-left: 0px;" valign="top">
                                        <table align="center" border="0" cellpadding="0" cellspacing="0" class="divider_content" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 1px solid #BFE8CE; width: 100%;"
                                          valign="top" width="100%">
                                          <tbody>
                                            <tr style="vertical-align: top;" valign="top">
                                              <td style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top"><span></span></td>
                                            </tr>
                                          </tbody>
                                        </table>
                                      </td>
                                    </tr>
                                  </tbody>
                                </table>
                                <!--[if (!mso)&(!IE)]><!-->
                              </div>
                              <!--<![endif]-->
                            </div>
                          </div>
                          <!--[if (mso)|(IE)]></td></tr></table><![endif]-->
                          <!--[if (mso)|(IE)]></td></tr></table></td></tr></table><![endif]-->
                        </div>
                      </div>
                    </div>          
                    <!-- /////////////////////////////////////////////////// -->
                    ' . $reservations . '
                    <!-- /////////////////////////////////////////////////// -->          
                    <div style="background-color:#edddff;">
                      <div class="block-grid" style="Margin: 0 auto; min-width: 320px; max-width: 500px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: #f0ffed;">
                        <div style="border-collapse: collapse;display: table;width: 100%;background-color:#f0ffed;">
                          <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#edddff;"><tr><td align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:500px"><tr class="layout-full-width" style="background-color:#f0ffed"><![endif]-->
                          <!--[if (mso)|(IE)]><td align="center" width="500" style="background-color:#f0ffed;width:500px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 0px; padding-left: 0px; padding-top:5px; padding-bottom:0px;"><![endif]-->
                          <div class="col num12" style="min-width: 320px; max-width: 500px; display: table-cell; vertical-align: top; width: 500px;">
                            <div style="width:100% !important;">
                              <!--[if (!mso)&(!IE)]><!-->
                              <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:5px; padding-bottom:0px; padding-right: 0px; padding-left: 0px;">
                                <!--<![endif]-->
                                <table border="0" cellpadding="0" cellspacing="0" class="divider" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;"
                                  valign="top" width="100%">
                                  <tbody>
                                    <tr style="vertical-align: top;" valign="top">
                                      <td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 10px; padding-right: 20px; padding-bottom: 10px; padding-left: 20px;" valign="top">
                                        <table align="center" border="0" cellpadding="0" cellspacing="0" class="divider_content" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 1px solid #BFE8CE; width: 100%;"
                                          valign="top" width="100%">
                                          <tbody>
                                            <tr style="vertical-align: top;" valign="top">
                                              <td style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top"><span></span></td>
                                            </tr>
                                          </tbody>
                                        </table>
                                      </td>
                                    </tr>
                                  </tbody>
                                </table>
                                <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 20px; padding-left: 20px; padding-top: 10px; padding-bottom: 40px; font-family: Arial, sans-serif"><![endif]-->
                                <div style="color:#333333;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;line-height:1.2;padding-top:10px;padding-right:20px;padding-bottom:40px;padding-left:20px;">
                                  <div style="font-size: 14px; line-height: 1.2; color: #333333; font-family: Arial, Helvetica Neue, Helvetica, sans-serif; mso-line-height-alt: 17px;">
                                    <p style="font-size: 14px; line-height: 1.2; word-break: break-word; mso-line-height-alt: 17px; margin: 0;"><span style="font-size: 14px; color: #333333; text-transform: uppercase"><strong>' . $firstName . ' ' . $lastName . '</strong></span></p>
                                  </div>
                                </div>
                                <!--[if mso]></td></tr></table><![endif]-->
                                <!--[if (!mso)&(!IE)]><!-->
                              </div>
                              <!--<![endif]-->
                            </div>
                          </div>
                          <!--[if (mso)|(IE)]></td></tr></table><![endif]-->
                          <!--[if (mso)|(IE)]></td></tr></table></td></tr></table><![endif]-->
                        </div>
                      </div>
                    </div>
                    <div style="background-color:#edddff;">
                      <div class="block-grid" style="Margin: 0 auto; min-width: 320px; max-width: 500px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: #ffffff;">
                        <div style="border-collapse: collapse;display: table;width: 100%;background-color:#ffffff;">
                          <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#edddff;"><tr><td align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:500px"><tr class="layout-full-width" style="background-color:#ffffff"><![endif]-->
                          <!--[if (mso)|(IE)]><td align="center" width="500" style="background-color:#ffffff;width:500px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 0px; padding-left: 0px; padding-top:20px; padding-bottom:40px;"><![endif]-->
                          <div class="col num12" style="min-width: 320px; max-width: 500px; display: table-cell; vertical-align: top; width: 500px;">
                            <div style="width:100% !important;">
                              <!--[if (!mso)&(!IE)]><!-->
                              <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:20px; padding-bottom:40px; padding-right: 0px; padding-left: 0px;">
                                <!--<![endif]-->
                                <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 35px; padding-left: 35px; padding-top: 10px; padding-bottom: 10px; font-family: Arial, sans-serif"><![endif]-->
                                <div style="color:#999999;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;line-height:1.5;padding-top:10px;padding-right:35px;padding-bottom:10px;padding-left:35px;">
                                  <div style="font-size: 14px; line-height: 1.5; color: #999999; font-family: Arial, Helvetica Neue, Helvetica, sans-serif; mso-line-height-alt: 21px;">
                                    <p style="font-size: 14px; line-height: 1.5; word-break: break-word; mso-line-height-alt: 21px; margin: 0;"><span style="color: #333333; font-size: 14px;"><strong>Informations &amp; conditions</strong></span></p><br>
                                    <p style="font-size: 14px; line-height: 1.5; word-break: break-word; mso-line-height-alt: 21px; margin: 0;"><span style="font-size: 14px;"> </span></p>
                                    <p style="font-size: 14px; line-height: 1.5; word-break: break-word; mso-line-height-alt: 21px; margin: 0;"><span style="font-size: 14px;">La caisse et le bar ouvrent une heure avant le début du spectacle. Nous gardons vos places jusqu&#39; à 15 minutes avant le début de la représentation.</span></p>
                                  </div>
                                </div>
                                <!--[if mso]></td></tr></table><![endif]-->
                                <table border="0" cellpadding="0" cellspacing="0" class="divider" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;"
                                  valign="top" width="100%">
                                  <tbody>
                                    <tr style="vertical-align: top;" valign="top">
                                      <td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 10px; padding-right: 10px; padding-bottom: 10px; padding-left: 10px;" valign="top">
                                        <table align="center" border="0" cellpadding="0" cellspacing="0" class="divider_content" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 1px solid #E1E1E1; width: 100%;"
                                          valign="top" width="100%">
                                          <tbody>
                                            <tr style="vertical-align: top;" valign="top">
                                              <td style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top"><span></span></td>
                                            </tr>
                                          </tbody>
                                        </table>
                                      </td>
                                    </tr>
                                  </tbody>
                                </table>
                                <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 35px; padding-left: 35px; padding-top: 10px; padding-bottom: 10px; font-family: Arial, sans-serif"><![endif]-->
                                <div style="color:#999999;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;line-height:1.5;padding-top:10px;padding-right:35px;padding-bottom:10px;padding-left:35px;">
                                  <div style="font-size: 14px; line-height: 1.5; color: #999999; font-family: Arial, Helvetica Neue, Helvetica, sans-serif; mso-line-height-alt: 21px;">
                                    <p style="font-size: 14px; line-height: 1.5; word-break: break-word; mso-line-height-alt: 21px; margin: 0;"><span style="color: #333333; font-size: 14px;"><strong>Nous sommes à votre disposition</strong></span></p><br>
                                    <p style="font-size: 14px; line-height: 1.5; word-break: break-word; mso-line-height-alt: 21px; margin: 0;"><span style="font-size: 14px;"> </span></p>
                                    <p style="font-size: 14px; line-height: 1.5; word-break: break-word; mso-line-height-alt: 21px; margin: 0;"><span style="font-size: 14px;">reservation.tlh@sierre.ch | tlh-sierre.ch | 027 452 02 97 | La billetterie est ouverte du mardi au vendredi de 14h à 17h et une heure avant le début du spectacle.</span></p>
                                  </div>
                                </div>
                                <!--[if mso]></td></tr></table><![endif]-->
                                <table border="0" cellpadding="0" cellspacing="0" class="divider" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;"
                                  valign="top" width="100%">
                                  <tbody>
                                    <tr style="vertical-align: top;" valign="top">
                                      <td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 10px; padding-right: 10px; padding-bottom: 10px; padding-left: 10px;" valign="top">
                                        <table align="center" border="0" cellpadding="0" cellspacing="0" class="divider_content" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 1px solid #E1E1E1; width: 100%;"
                                          valign="top" width="100%">
                                          <tbody>
                                            <tr style="vertical-align: top;" valign="top">
                                              <td style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top"><span></span></td>
                                            </tr>
                                          </tbody>
                                        </table>
                                      </td>
                                    </tr>
                                  </tbody>
                                </table>
                                <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 35px; padding-left: 35px; padding-top: 10px; padding-bottom: 10px; font-family: Arial, sans-serif"><![endif]-->
                                <div style="color:#999999;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;line-height:1.5;padding-top:10px;padding-right:35px;padding-bottom:10px;padding-left:35px;">
                                  <div style="font-size: 14px; line-height: 1.5; color: #999999; font-family: Arial, Helvetica Neue, Helvetica, sans-serif; mso-line-height-alt: 21px;">
                                    <p style="font-size: 14px; line-height: 1.5; word-break: break-word; mso-line-height-alt: 21px; margin: 0;"><span style="color: #333333;"><strong>Nous trouve</strong><strong>r</strong></span></p><br>
                                    <p style="font-size: 14px; line-height: 1.5; word-break: break-word; mso-line-height-alt: 21px; margin: 0;"> </p>
                                    <p style="font-size: 14px; line-height: 1.5; word-break: break-word; mso-line-height-alt: 21px; margin: 0;"><span style="">Route ancien Sierre 13 | Case postale 96 | CH-3960 Sierre | Le TLH est totalement accessible aux personnes à mobilité réduite</span></p>
                                  </div>
                                </div>
                                <!--[if mso]></td></tr></table><![endif]-->
                                <table cellpadding="0" cellspacing="0" class="social_icons" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" valign="top" width="100%">
                                  <tbody>
                                    <tr style="vertical-align: top;" valign="top">
                                      <td style="word-break: break-word; vertical-align: top; padding-top: 60px; padding-right: 10px; padding-bottom: 10px; padding-left: 10px;" valign="top">
                                        <table activate="activate" align="center" alignment="alignment" cellpadding="0" cellspacing="0" class="social_table" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: undefined; mso-table-tspace: 0; mso-table-rspace: 0; mso-table-bspace: 0; mso-table-lspace: 0;"
                                          to="to" valign="top">
                                          <tbody>
                                            <tr align="center" style="vertical-align: top; display: inline-block; text-align: center;" valign="top">
                                              <td style="word-break: break-word; vertical-align: top; padding-bottom: 20px; padding-right: 10px; padding-left: 10px;" valign="top"><a href="https://plus.google.com/https://www.google.de/maps/dir//Route+de+Ancien+Sierre+13,+Siders/@46.2856142,7.527477,17z/data=!4m8!4m7!1m0!1m5!1m1!1s0x478f1f748189d03d:0xbe248e83d10b8393!2m2!1d7.5296657!2d46.2856142?hl=fr"
                                                  target="_blank"><img alt="Google Maps" height="32" src="https://uploads-ssl.webflow.com/5abe088eed72f876bf4b2347/5b9e68d308470edc7cfa0214_google-maps.png" style="text-decoration: none; -ms-interpolation-mode: bicubic; height: auto; border: none; display: block;" title="Google Maps" width="32"/></a></td>
                                              <td style="word-break: break-word; vertical-align: top; padding-bottom: 20px; padding-right: 10px; padding-left: 10px;" valign="top"><a href="https://www.facebook.com/tlhsierre/" target="_blank"><img alt="Facebook" height="32" src="https://uploads-ssl.webflow.com/5abe088eed72f876bf4b2347/5dd84cbf05da584bc09371f3_facebook_logo_2019.png" style="text-decoration: none; -ms-interpolation-mode: bicubic; height: auto; border: none; display: block;" title="Facebook" width="32"/></a></td>
                                            </tr>
                                          </tbody>
                                        </table>
                                      </td>
                                    </tr>
                                  </tbody>
                                </table>
                                <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 10px; padding-left: 10px; padding-top: 10px; padding-bottom: 10px; font-family: Arial, sans-serif"><![endif]-->
                                <div style="color:#999999;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;line-height:1.5;padding-top:10px;padding-right:10px;padding-bottom:10px;padding-left:10px;">
                                  <div style="font-size: 14px; line-height: 1.5; color: #999999; font-family: Arial, Helvetica Neue, Helvetica, sans-serif; mso-line-height-alt: 21px;">
                                    <p style="text-align: center; line-height: 1.5; word-break: break-word; font-size: 14px; mso-line-height-alt: 21px; margin: 0;"><span style="font-size: 14px;">Développé avec <span style="color: #ff00ff;">♥</span> par <a href="https://www.anthonysalamin.ch/" rel="noopener" style="text-decoration: none; color: #0068A5;" target="_blank">anthonysalamin.ch</a></span><br/>
                                      <span
                                        style="font-size: 14px;">© copyright 2011 — ' . $year .' <a href="https://www.tlh-sierre.ch/" rel="noopener" style="text-decoration: none; color: #0068A5;" target="_blank">TLH - Sierre</a></span><br/><span style="font-size: 14px;">Vous recevez cet email à la suite de votre:</span></p><br>
                                    <p style="text-align: center; line-height: 1.5; word-break: break-word; font-size: 14px; mso-line-height-alt: 21px; margin: 0;"><span style="font-size: 14px;"> </span></p>
                                    <p style="text-align: center; line-height: 1.5; word-break: break-word; font-size: 14px; mso-line-height-alt: 21px; margin: 0;"><span style="font-size: 14px;"><span style="color: #333333;"><strong>Réservation nº</strong></span> ' . $date .' | ' . $hour .'</span>
                                    </p><br><br>
                                  </div>
                                </div>
                                <!--[if mso]></td></tr></table><![endif]-->
                                <div align="center" class="img-container center fixedwidth" style="padding-right: 0px;padding-left: 0px;">
                                  <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr style="line-height:0px"><td style="padding-right: 0px;padding-left: 0px;" align="center"><![endif]-->
                                  <div style="font-size:1px;line-height:40px"> </div><img align="center" alt="Image" border="0" class="center fixedwidth" src="https://uploads-ssl.webflow.com/5abe088eed72f876bf4b2347/5b9e69e6b4189e863ad18d80_icon-tlh.png" style="text-decoration: none; -ms-interpolation-mode: bicubic; border: 0; height: auto; width: 100%; max-width: 100px; display: block;"
                                    title="TLH logo" width="100" />
                                  <!--[if mso]></td></tr></table><![endif]-->
                                </div>
                                <!--[if (!mso)&(!IE)]><!-->
                              </div>
                              <!--<![endif]-->
                            </div>
                          </div>
                          <!--[if (mso)|(IE)]></td></tr></table><![endif]-->
                          <!--[if (mso)|(IE)]></td></tr></table></td></tr></table><![endif]-->
                        </div>
                      </div>
                    </div>
                    <!--[if (mso)|(IE)]></td></tr></table><![endif]-->
                  </td>
                </tr>
              </tbody>
            </table>
            <!--[if (IE)]></div><![endif]-->
          </body>

          </html>
        ';

        // PHPmailer setup
        $mail->CharSet = 'UTF-8';
        // $mail->SMTPDebug = SMTP::DEBUG_SERVER;
        // $mail->SMTPDebug = 2 ; // debug
        $mail->isSMTP();
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'tls'; // ssl || tls
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 587; // 465 || 587
        $mail->Username = $from;
        $mail->Password = 'Gmail240788!';
        
        // recipients
        $mail->AddReplyTo('reservation.tlh@sierre.ch', 'Réservation TLH');
        $mail->setFrom($from, 'TLH Sierre');
        $mail->addAddress($to);

        // content
        $mail->Subject = 'Confirmation | 🎫  ' . $eventName . '';
        $mail->isHTML(true);
        //Build a simple message body
        $mail->Body = $message;

        $mail->send();
        // header("Location: https://www.further.design/thanks");
        echo "YAY, Confirmation envoyée à $to";
    } catch (Exception $e) {
        echo nl2br("Ooops, erreur technique: \n {$mail->ErrorInfo}");
    } // end try

  /*
  * Mailchimp API
  * add & update subscriber
  * https://bit.ly/32qngLM
  */

  $data = [
    'email'     => $to,
    'status'    => 'subscribed',
    'firstname' => $firstName,
    'lastname'  => $lastName
];

// handle mailchimp checkbox
$newsletter = '';
if (empty($_POST["Newsletter"])) {
    // echo "user does not agree to subscribe";
    $newsletter .= "non";
}
else {
    syncMailchimp($data);
    $newsletter .= "oui";
}

// add or update subscriber
function syncMailchimp($data) {
    $apiKey = '👻';
    $listId = '407961';

    $memberId = md5(strtolower($data['email']));
    $dataCenter = substr($apiKey,strpos($apiKey,'-')+1);
    $url = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/lists/' . $listId . '/members/' . $memberId;

    $json = json_encode([
        'email_address' => $data['email'],
        'status'        => $data['status'], // "subscribed","unsubscribed","cleaned","pending"
        'merge_fields'  => [
            'FNAME'     => $data['firstname'],
            'LNAME'     => $data['lastname']
        ]
    ]);

    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $apiKey);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);                                                                                                                 

    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return $httpCode;
}

/*
* Google Sheet API
* Goal: update a Google Sheet with user inputs via PHP
* Actual excel sheet URL: https://docs.google.com/spreadsheets/d/17vP123KdhpzNofSUY4pPYgrbOmmHFObbaKz_bP96o6Q
* Tutorial URL: https://www.youtube.com/watch?v=iTZyuszEkxI
* Login to API: https://console.developers.google.com/apis/credentials?project=tlh-sierre
*/

// Google Sheet API dependencies
require_once '/home/tlhspauf/public_html/google-api-php-client/vendor/autoload.php';

$client = new Google_Client();
$client->setApplicationName("TLH Reservation Count");
$client->addScope(Google_Service_Sheets::SPREADSHEETS);
$client->setAuthConfig('/home/tlhspauf/public_html/json/credentials_v5.json'); // JSON file from Google Sheet API
$client->setDeveloperKey("👻"); // Google Sheet API key 

$service = new Google_Service_Sheets($client);
$spreadsheetId = "17vP123KdhpzNofSUY4pPYgrbOmmHFObbaKz_bP96o6Q"; // ID from the Sheet URL
$range ="booking"; // name of the Google Sheet
$values = [];

foreach ($inputs as $key => $value) {
    if (strpos($key, 'à') !== false && $value != 0) {

        // handle key + value formating
        $detail = str_replace("_"," ","$key");
        $number = (int)$value;
              
        // inject formating into $values variable to update the Google Sheet API with
        $values[] = [
            $date, $eventName, $detail, $number, $firstName, $lastName, $to, $newsletter, $assistance, $hour, 'dynamic PHP script'
        ];
    }
}

$body = new Google_Service_Sheets_ValueRange([
    "values" => $values
]);

$params = [
    "valueInputOption" => "RAW"
];

$insert = [
    "insertDataOption" => "INSERT_ROWS"
];

$result = $service->spreadsheets_values->append(
    $spreadsheetId,
    $range,
    $body,
    $params,
    $insert
);

?>