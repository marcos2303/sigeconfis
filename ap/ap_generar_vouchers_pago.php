<form name="frmentrada" id="frmentrada" method="post" target="iReporte">
<input type="hidden" name="registro" id="registro" value="<?=$registro?>" />
<table width="1000" align="center" cellpadding="0" cellspacing="0">
    <tr>
        <td>
        	<div class="header">
            <ul id="tab">
            <!-- CSS Tabs -->
            <?php
			if ($_PARAMETRO['CONTONCO'] == "S") {
				$anz = "ap_generar_vouchers_pagos_voucher";
				?>
				<li id="li1" onclick="current($(this));" class="current">
					<a href="#" onclick="cargarPagina(document.getElementById('frmentrada'), 'gehen.php?anz=ap_generar_vouchers_pagos_voucher');">Voucher Contable</a>
				</li>
				<?
			} else { $anz = "ap_generar_vouchers_pagos_voucher_pub20"; $current = "current"; }
						
			if ($_PARAMETRO['CONTPUB20'] == "S") {
				?>
                <li id="li2" onclick="current($(this));" class="<?=$current?>">
                    <a href="#" onclick="cargarPagina(document.getElementById('frmentrada'), 'gehen.php?anz=ap_generar_vouchers_pagos_voucher_pub20');">Voucher Contable (Pub. 20)</a>
                </li>
                <?
			}
			?>
            </ul>
            </div>
        </td>
    </tr>
</table>
</form>

<center>
<iframe name="iReporte" id="iReporte" style="border-left:solid 1px #CDCDCD; border-right:solid 1px #CDCDCD; border-bottom:solid 1px #CDCDCD; border-top:0; width:1000px; height:500px;" src="gehen.php?anz=<?=$anz?>&registro=<?=$registro?>"></iframe>
</center>