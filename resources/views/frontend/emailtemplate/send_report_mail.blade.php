<!Doctype html>
<html>
   <head>
      <meta charset="utf-8">
      <title>{{env('APP_NAME')}}</title>
   </head>
   <body>
      <div style="font-family:'Lato',sans-serif;font-weight:normal;margin:0;padding:0;text-align:left;color:#333333;font-size:12px">
         <center>
            <table border="0" cellspacing="0" cellpadding="0" style="border-collapse:collapse;padding:0;margin:0px auto;max-width:800px">
               <tbody>
                  <tr>
                     <td bgcolor="#f7f7f7" style="font-family:'Lato',sans-serif;font-weight:normal;border-collapse:collapse;vertical-align:top;padding:24px 35px;margin:0;height:35px;border-bottom:2px #B58324 solid;">
                        <table style="width:100%;border-collapse:collapse;padding:0;margin:0" border="0" cellpadding="0" cellspacing="0">
                           <tbody>
                              <tr>
                                 <td style="font-family:'Lato',sans-serif;font-weight:normal;border-collapse:collapse;vertical-align:top;padding:0;margin:0;width:40%">
                                    <a href="<?php echo url();?>" style="color:#fff;text-decoration:none" target="_blank"><img src="<?php echo url();?>/public/frontend/images/logo.png" style="max-width: 150px;height:auto;" alt="" style="text-align:center;"></a>
                                 </td>
                              </tr>
                           </tbody>
                        </table>
                     </td>
                  </tr>
                  <tr style="background:#000;">
                     <td style="font-family:'Lato',sans-serif;font-weight:normal;border-collapse:collapse;padding:0;margin:0">
                        <table style="width:100%;border-collapse:collapse;padding:0;margin:0" cellpadding="0" cellspacing="0">
                           <tbody>
                              <tr>
                                 <td style="font-family:'Lato',sans-serif;font-weight:normal;border-collapse:collapse;vertical-align:top;padding:30px 15px;margin:0;background:#f7f7f7">
                                    <table cellpadding="0" cellspacing="0" border="0" style="border-collapse:collapse;padding:0;margin:0">
                                       <tbody>
                                          <tr>
                                             <td style="font-family:'Lato',sans-serif;font-weight:normal;border-collapse:collapse;vertical-align:top;padding:0px 20px 15px;margin:0;line-height:18px">
                                                <h1 style="font-family:'Lato',sans-serif;font-weight:normal;font-size:24px;line-height:28px;color:#666666"><span style="color:#B58324;">Hi <?php echo $advert_user_name; ?></span>,</h1>
                                                <p style="font-family:'Lato',sans-serif;font-weight:normal;font-size:16px;line-height:20px;color:#808080;margin:0;margin-bottom:20px;display:block;text-align:left;padding:0"><span>Customer Name: </span><?php echo $customer_name; ?>.</p>
                                               <p style="font-family:'Lato',sans-serif;font-weight:normal;font-size:16px;line-height:20px;color:#808080;margin:0;margin-bottom:20px;display:block;text-align:left;padding:0"><span>Customer Email: </span><?php echo $customer_email; ?>.</p>

                                               <p style="font-family:'Lato',sans-serif;font-weight:normal;font-size:16px;line-height:20px;color:#808080;margin:0;margin-bottom:20px;display:block;text-align:left;padding:0"><span>Customer Phone: </span><?php echo $customer_phone; ?>.</p>

                                                <p style="font-family:'Lato',sans-serif;font-weight:normal;font-size:16px;line-height:20px;color:#808080;margin:0;margin-bottom:20px;display:block;text-align:left;padding:0"><?php echo $message_body; ?>.</p>
                                                
                                                <p style="font-family:'Lato',sans-serif;font-weight:600;font-size:20px;line-height:20px;margin:0;margin-bottom:20px;display:block;text-align:left;padding:0;font-style:normal; text-align:center;color:#B58324;">Thank you</p>
                                             </td>
                                          </tr>
                                       </tbody>
                                    </table>
                                 </td>
                              </tr>
                           </tbody>
                        </table>
                     </td>
                  </tr>
                  <tr>
                     <td bgcolor="#B58324" style="font-family:'Lato',sans-serif;font-weight:normal;border-collapse:collapse;vertical-align:top;padding:10px 35px 0px 35px;margin:0;height:10px">
                        <table style="width:100%;border-collapse:collapse;padding:0;margin:0" border="0" cellpadding="0" cellspacing="0">
                           <tbody>
                              <tr>
                                 <td align="center" style="font-family:'Lato',sans-serif;font-size:14px;font-weight:normal;border-collapse:collapse;vertical-align:top;padding:0;margin:0;padding-bottom:10px">
                                    <p style="font-family:'Lato',sans-serif;font-size:14px;color:#fff;">© Copyright <?php echo date('Y'); ?> <a style="color:#fff;text-decoration:none;" href="<?php echo url();?>">www.dageega.com</a>
                                 </td>
                              </tr>
                           </tbody>
                        </table>
                     </td>
                  </tr>
               </tbody>
            </table>
         </center>
      </div>
   </body>
</html>