//<![CDATA[
$(document).ready(
	function () {
		// 'select' the first form
		changeForm();

		// bind actions
		$(".form-find").bind("click",
			function (event) {
				useExisting(this);
				return false;
			}
		);
		$("#SheetViewName").bind("change",
			function (event) {
				changeForm();
				return false;
			}
		);
		$(".form-grow").bind("click",
			function (event) {
				growForm(this);
				return false;
			}
		);
		$(".form-shrink").bind("click",
			function (event) {
				shrinkForm(this);
				return false;
			}
		);
	}
);
//]]>
