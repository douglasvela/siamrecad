
<label for="input-file-now">Adjunte la factura</label>
<input type="file" id="file" name="file" class="dropify" accept="image/*,application/pdf,application/msword" data-height="300" data-default-file="<?php if(!empty($_GET["ruta"])){ echo $_GET["ruta"]; } ?>"/>