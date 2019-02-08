
// Set current menu item to center position in list
window.addEventListener('load', function() {
  console.log('All assets are loaded')
  let mainMenu = document.querySelector('#primary-menu');
  // console.log(mainMenu);
  let menuItems = mainMenu.querySelectorAll('li');
  // console.log(menuItems);
  for (var i = 0; i < menuItems.length; i++) {
    menuItems[i].addEventListener('click', printDetails);
  }
  
  function printDetails(e) {
    e.preventDefault();
    console.log(e);
    console.log(mainMenu[i]);
    console.log(mainMenu.childNodes[i]);
  }

  const centerIndex = (arr, number) => {
    let targetIndex = arr.indexOf(number);
    if (targetIndex < 0) {
      console.log(`The number you provided is not part of the array.`);
      return;
    }
    let newPosition = 0;
    if (arr.length % 2 != 0) {
      newPosition = Math.floor(arr.length / 2);
    } else {
      newPosition = arr.length / 2;
    }
    console.log(targetIndex);
    console.log(arr[newPosition]);
    console.log(arr.indexOf(arr[newPosition]));
    console.log(arr.length)
    console.log(`arr before: ${arr}`);
    let steps = Math.abs(targetIndex - arr.indexOf(arr[newPosition]));
    let appendLast = [];
    if (targetIndex > newPosition) {
    for (i = 0; i < arr.length; i++) {
      if ((i - steps) < 0 ) {
        console.log( i - steps);
        appendLast.push(arr[i]);
      }
    }
    for (i = 0; i < appendLast.length; i++) {
      arr.splice(0, 1); // Remove the first items in the array
      arr.push(appendLast[i]); // Add them to the back of the array
      }
    }
    if (targetIndex < newPosition) {
      for (i = 0; i < arr.length; i++) {
        if ((i + steps) > arr.length - 1 ) {
          // console.log(appendLast)
          appendLast.push(arr[i]);
        } 
      }
      for (i = 0; i < appendLast.length; i++) {
        arr.splice((arr.length - 1), 1); // Remove the last items in the array
        arr.unshift(appendLast[i]); // Add them to the front of the array
      }
    }
    console.log(`steps: ${steps}`);
    console.log(`appendLast: ${appendLast}`);
    console.log(`arr after: ${arr}`);
  }

})