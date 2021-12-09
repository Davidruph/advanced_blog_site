
let autocomplete;
let address1Field;
let address2Field;

function initAutocomplete() {
  var address1Field = document.querySelector("#from");
  var address2Field = document.querySelector("#to");
  // Create the autocomplete object, restricting the search predictions to
  // addresses in the US and Canada.
  autocomplete = new google.maps.places.Autocomplete(address1Field, {
    componentRestrictions: { country: ["us", "ca"] }, 
    fields: ["address_components", "geometry"],
    types: ["address"],
  });
  address1Field.focus();

  var autocomplete = new google.maps.places.Autocomplete(address2Field, {
    componentRestrictions: { country: ["us", "ca"] }, 
    fields: ["address_components", "geometry"],
    types: ["address"],
  });
  address1Field.focus();
  // When the user selects an address from the drop-down, populate the
  // address fields in the form.
  autocomplete.addListener("place_changed", fillInAddress);
}

function fillInAddress() {
  // Get the place details from the autocomplete object.
  const place = autocomplete.getPlace();

}