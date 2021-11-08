@extends('layouts.app')
<style type="text/css">
  @page 
    {
      /* sheet-size: A3-L;  */

        size: auto;   /* auto is the initial value */
        margin: 0mm;  /* this affects the margin in the printer settings */
    }
</style>
@section('content')


<!-- BODY -->
<!-- Set message background color (twice) and text color (twice) -->
<body topmargin="0" rightmargin="0" bottommargin="0" leftmargin="0" marginwidth="0" marginheight="0" width="100%" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; width: 100%; height: 100%; -webkit-font-smoothing: antialiased; text-size-adjust: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; line-height: 100%;
  background-color: #F0F0F0;
  color: #000000;"
  bgcolor="#F0F0F0"
  text="#000000">

<!-- SECTION / BACKGROUND -->
<!-- Set message background color one again -->
<table width="100%" align="center" border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; width: 100%;" class="background"><tr><td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0;"
  bgcolor="#F0F0F0">

<!-- WRAPPER -->
<!-- Set wrapper width (twice) -->

<!-- WRAPPER / CONTEINER -->
<!-- Set conteiner background color -->
<table border="0" cellpadding="0" cellspacing="0" align="center"
  bgcolor="#FFFFFF" style="border-collapse: collapse; border-spacing: 0; padding: 0; width:100%; max-width: 600px; min-width: 300px; border:0px solid orange;-webkit-box-shadow: 0px 6px 15px 0px rgba(0,0,0,0.65);-moz-box-shadow: 0px 6px 15px 0px rgba(0,0,0,0.65);box-shadow: 0px 6px 15px 0px rgba(0,0,0,0.65);border-radius:10px;" class="container">


  <tr>  
    <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%;
      padding-top: 25px;" class="line"><hr
      color="#E0E0E0" align="center" width="100%" size="1" noshade style="margin: 0; padding: 0;" />
    </td>
  </tr>
  
  <tr><td><div style="text-align:center;padding-top:20px;font-size:25px;font-family:Lato;font-weight:300;">{!! strtoupper($product->name) !!}</div></td>
  </tr>

            <tr>

       
              <td align="left">
              @foreach($qr_codes as $key=> $codes)
                <div style="display: inline-block;">
                  <table width="50" align="left" cellpadding="0" cellspacing="0" border="0" style="padding: 0; margin: 0; mso-table-lspace:0pt; mso-table-rspace:0pt;"  class="article">
                  <tbody>
                    <tr> <td colspan="3" height="40"></td> </tr>
                    <tr>
                      <td width="80" style="width: 8%;"></td>
                      <td align="center">
                      <h3 border="0" style="border: none; line-height: 14px; color: #212121; font-family: Verdana, Geneva, sans-serif; font-size: 16px; text-transform: uppercase; font-weight: normal; overflow: hidden;">{!!strtoupper($product->name)!!}
                        </h3>
                        <div class="imgs">
                       
                          <a href="#" target="_blank" border="0" style="border: none; display: block; outline: none; text-decoration: none; line-height: 40px; height: 60px; color: #ffffff; font-family: Verdana, Geneva, sans-serif; margin: 0 30px; -webkit-text-size-adjust:none;">
                          {!! QrCode::size(100)->generate($codes->qrcode_data); !!}
                   
                          </a>
                        </div>
                        <h3 border="0" style="border: none; line-height: 14px; color: #212121; font-family: Verdana, Geneva, sans-serif; font-size: 16px; text-transform: uppercase; font-weight: normal; overflow: hidden; margin:50px 0 0px 0;">{{$codes->qrcode_data}}
                        </h3>
                       
                      </td>
                      <td width="80" style="width: 8%;"></td>
                    </tr>
                    <tr><td colspan="3" height="10"></td></tr>					
                    <tr>
                      <td colspan="3" height="5" valign="top" align="center">
                       
                      </td>
                    </tr>						
                  </tbody>
                  </table>
                </div>
                @endforeach
									
              </td>
            </tr>
            <tr> <td colspan="5" height="40"></td> </tr>
        













          

  


<!-- End of WRAPPER -->
</table>



</table>



</body>
@endsection

@push('head')
<script type="text/javascript">
$(document).ready(function(){
  // window.print();
});
</script>

@endpush
