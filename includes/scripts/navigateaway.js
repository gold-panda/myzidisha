 function populateArrays()
  {	
    // assign the default values to the items in the values array
    for (var i = 0; i < ids.length; i++)
    {
      var elem = document.getElementById(ids[i]);
      if (elem)
        if ( elem.type == 'radio')
          values[i] = elem.checked;
	  else if(elem.type == 'select-one') {
		 
		values[i] =	elem.options[elem.selectedIndex].value;
	  }
       else
          values[i] = elem.value;
    }      
  }
   window.onbeforeunload = confirmExit;
  function confirmExit()
  {		
	 
    if (needToConfirm)
    { 
      // check to see if any changes to the data entry fields have been made
      for (var i = 0; i < values.length; i++)
      {
        var elem = document.getElementById(ids[i]);
        if (elem)
          if ((elem.type == 'radio') && values[i] != elem.checked) {
				return "Your changes have not been saved.  Do you wish to discard your changes?";
			}
          else if (!(elem.type == 'radio') && elem.value != values[i]) {
			 return "Your changes have not been saved.  Do you wish to discard your changes?";
		  }
      }

      // no changes - return nothing      
    }
  }

  
  function confirmChange(){ 
	var retval = true;
	for (var i = 0; i < values.length; i++)
      {
        var elem = document.getElementById(ids[i]);
		var m = ids[i].match(/\d+/g);
		if (elem)
          if ((elem.type == 'radio') && values[i] != elem.checked) {
			$('#rowid'+m).children("td").css('border-top', 'solid 2px red');
			$('#rowid'+m).children("td").css('border-bottom', 'solid 2px red');
			$('#rowid'+m).children('td:first-child').css('border-left', 'solid 2px red');
			$('#rowid'+m).children('td:last').css('border-right', 'solid 2px red');
				retval=false;
			}
          else if (!(elem.type == 'radio') && elem.value != values[i]) { 
			 $('#rowid'+m).children("td").css('border-top', 'solid 2px red');
			 $('#rowid'+m).children("td").css('border-bottom', 'solid 2px red');
			$('#rowid'+m).children('td:first-child').css('border-left', 'solid 2px red');
			$('#rowid'+m).children('td:last').css('border-right', 'solid 2px red');
			 retval=false;
		  }
      }
	  if(!retval) {
		return "Your changes have not been saved.  Do you wish to discard your changes?";
	  }
  }
