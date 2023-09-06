
async function fetchFavourites(url){
  const repsonse = await fetch(url);
  const data = await repsonse.json();
  displayData(data);
}

//call function to fetch data
fetchFavourites('app/select.php');

function displayData(data){
  //select element from HTML where we'll put our tv show
  const display = document.querySelector('#display');
  display.innerHTML = '';

  //create an unordered list
  let ul = document.createElement('ul');
  let a = document.createElement('a');

  
  data.forEach((user)=>{
    //console.log(user);
    //create items, add text and append to the list
    let li = document.createElement('li');
    // * _update * added an ugly "edit" link */
    let a = document.createElement('a');
    a.href = `#${user.demoID}`;
    a.dataset.demoID = user.demoID;
    a.innerHTML = " edit tvshow";
    li.innerHTML = `${user.name} likes to watch ${user.tvshow}.`;
    li.appendChild(a);
    a.addEventListener('click', (event)=> {
      //wow lots of parameters
      updateForm(event, user.demoID, user.name, user.tvshow);
    });
    //see below for functionality
    // * end _update */
    ul.appendChild(li);
  })
  //don't forget to append your elements.
  display.appendChild(ul);
}

const submitButton = document.querySelector('#submit');
submitButton.addEventListener('click', getFormData);

function getFormData(event){
  event.preventDefault();

  //get the form data & call an async function
  const insertFormData = new FormData(document.querySelector('#insert-form'));
  let url = 'app/insert_v3.php';
  inserter(insertFormData, url);
}

async function inserter(data, url){
  const response = await fetch(url, {
    method: "POST",
    body: data
  });
  const confirmation = await response.json();

  console.log(confirmation);
  //call function again to refresh the page
  fetchFavourites('app/select.php');
}

// _update section **

function updateForm(event, demoID, user, tvshow){
  console.log(demoID, user, tvshow);
  //default is to navigate to an id i.e. reload page?
  event.preventDefault(); 

  // pseudo code: let's either 
    // populate the existing form OR
    // create a form element inline ** doing this one ** 

  //a few handy things
    //get href i.e. https://nortonb.web582.com/demo_db/index_update.html#63
      //then split into 2 array elements
      //then get the 2nd (index[1]) array element et voila... magic
    // console.log(event.target.href.split('#')[1]);
    // console.log(event.target.parentNode);
    // console.log(event.target.parentNode.textContent);
  //we click the link <a href...> so need to target parentNode i.e. <li>...
  let li = event.target.parentNode;

  event.target.parentNode.innerHTML = `<form id="update-form"><input type="hidden" name="demoID"  value="${demoID}"> <input type="text" name="full_name" value="${user}"> <input type="text" name="tvshow" value="${tvshow}">. <a href="#update" id="update">update</a></form>`;
  
  console.log(li.querySelector('#update'));
  li.querySelector('#update').addEventListener('click', (event)=>{
    event.preventDefault();
    console.log(event);
    //expecting "demoID", "full_name" and "tvshow" in form data.
    let updateData = new FormData(document.querySelector('#update-form'));
    //call function to "fetch" data, posting to app/update.php
    let url = 'app/update.php';
    updater(updateData, url);
  })

}

//again with the names!!
async function updater(data, url){
  const response = await fetch(url, {
    method: "POST",
    body: data
  });
  const confirmation = await response.json();

  console.log(confirmation);
  //call function again to refresh the page
  fetchFavourites('app/select.php');
}