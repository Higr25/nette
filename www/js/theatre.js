window.addEventListener("load", function()
{
  var cellNumber = " {$seat->seat_number}";
  var cell = document.getElementsByClassName("cell");
  var i;
  for (var i = 0; i < cell.length; i++) {
    cell[i].addEventListener("click", function()
    {
        document.getElementById("frm-reservationForm-seat_number").value = cellNumber;
    })
  }

  })
