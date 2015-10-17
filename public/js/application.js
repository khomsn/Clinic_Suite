$(document).ready(function() {

    // your stuff here
    // ...
	function numberWithCommas(x) {
					  var parts = x.toString().split(".");
					  parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
					  return parts.join(".");
				      }
  

});
        <script type="text/javascript">
        <!--
        String.prototype.reverse = function () {return this.split('').reverse().join('')};

        function Dollars (d) {this.ammount = typeof d == 'number' ? d : Number(d.toString().replace(/[$,]/g, ''))};

        Dollars.prototype.valueOf = function () {return this.ammount};

        Dollars.prototype.toString = function () {
        if (isNaN (this.ammount)) return NaN.toString();
        var l = Math.floor(Math.abs(this.ammount)).toString();
        var r = Math.round((Math.abs(this.ammount) % 1) * 100).toString();
        return [(this.ammount < 0 ? '-' : ''), '$', (l.length > 4 ? l.reverse().match(/\d{1,3}/g).join(',').reverse() : l),'.', (r < 10 ? '0' + r : r)].join('');
        }

        if (document.getElementsByTagName) onload = function () {
        var e, i;
        for (i = 0; e = document.getElementsByTagName('SPAN')[i]; i++) {if (/currency/i.test(e.className)) e.firstChild.data = new Dollars (e.firstChild.data)}
        }
        // -->
        </script>
