<!DOCTYPE>
<html>
	<head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    </head>
    <body>
        <table bgcolor="#e8e9ea" cellpadding="10" cellspacing="0" style="font-family: Arial,Helvetica,sans-serif; color: #333333; font-size: 12px; width: 100%;">
            <tbody>
                <tr>
                    <td align="center" valign="top">
						<table align="center" bgcolor="#ffffff" cellpadding="0" cellspacing="0" id="main-table-1" style="padding: 20px;
        				border: 1px solid #cccccc;
			            width: 80%;
			            border-radius: 10px;
			            border-collapse: collapse;
			            border-radius: 10px;
			            border-style: hidden;
			            /* box-shadow: 0 0 0 1px #cecece; */
			            box-shadow: 0px 2px 17px 0px rgba(0, 0, 0, 0.2);">
                            <thead>
                                <th style="padding:0 !important; border-radius: 10px 10px 0 0; overflow: hidden;">@include('emails.header')</th>
                            </thead>
							<tbody>
								<tr>
									<td style="padding: 10px 25px;">
										<div style="padding: 5px 0; margin: 10px 0;">
											<h6 style="
												font-size: 1rem;
											    /* font-weight: 700; */
											    margin: 1rem 0 2rem;"
    											>Уважаемый/уважаемая <b>{{ $user->username }}</b>, </h6>
											<p>{!! $text !!}</p>
											<p>Наша служба поддержки работает круглосуточно и доступна по телефону: +420 212 342 222 или по адресу: service@tvoyo.tv</p>
										</div>
										<hr style="border: 0; color: #ccc; border-bottom: 1px dashed #ccc; height: 1px; width: 100%; text-align: left;" />
										<div style="font-size: 11px; padding: 20px 0 10px;">
											<p><b>Желаем приятного просмотра!</b></p>
											<p>С уважением и признательностью,</p>
											<p>Команда TVOYOTV</p>
											<p>Пожалуйста, не отвечайте на данное сообщение, так как оно генерируется автоматически и служит только для информации.</p>
										</div>
									</td>
								</tr>
							</tbody>
						</table>
						<div style="color: #665; font-size: 11px; padding: 20px 50px 10px 50px; color: #9E9E9E;"">
                            <center>Copyright &copy; {{ date('Y') }} <a href="{{ $domain }}">{{ $domain }}</a></center>
                        </div>
					</td>
				</tr>
			</tbody>
		</table>
	</body>
	<style type="text/css">
        #main-table-1 {
            padding: 20px;
            border: 1px solid #cccccc;
            width: 80%;
            border-radius: 10px;
            border-collapse: collapse;
            border-radius: 10px;
            border-style: hidden;
            /* box-shadow: 0 0 0 1px #cecece; */
            box-shadow: 0px 2px 17px 0px rgba(0, 0, 0, 0.2);
        }

        #main-table-1 tr td {
            padding: 10px 25px;
        }
    </style>
</html>