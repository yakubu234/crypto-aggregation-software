<!-- Bootstrap & Font Awesome -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"/>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<!-- STYLES: Add custom CSS to replicate the "fancy exchange" look -->
<style>

/* Variables converted from SCSS */
:root {
    --dark-grey: #141414;
    --medium-grey: #555;
    --light-grey: #F8F8F8;
    --primary-blue: #244b7b;
    --primary-black: black;
    --accent-blue: #8acbff;
    --link: #fff399;
    --card-blue: #102a49;
    --positive: #7cd382;
    --negative: #e86666;
}

html, body {
  padding: 0;
  margin: 0;
  width: 100%;
  height: 100%;
  background: var(--primary-blue);
}
.timer-container {
    display: inline-flex;
    flex-direction: column;
    align-items: center;
    margin-top:30px;
    gap: 8px;               /* space between icon & text */
    padding: 8px 16px;
    margin-bottom: 20px;    /* spacing below the timer */
    background-color: var(--card-blue);
    color: var(--light-grey);
    font-size: 1.25rem;
    font-weight: 600;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
}

.current-time{
  color: #fff;
  font-size: 20px;
  font-weight: bold;
}

.timer-container i {
    font-size: 1.5rem;
    color: var(--accent-blue);
}

.app-container {
  box-sizing: border-box;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  align-items: center;
  font-family: 'Montserrat', sans-serif;
}

.app-container h1 {
  width: 70vw;
  padding: 10px;
  margin-left: 20px;
  color: var(--light-grey);
}

.app-container h4 {
  width: 70vw;
  padding: 0px 10px;
  margin-top: -20px;
  margin-left: 20px;
  color: var(--accent-blue);
}

.app-container p {
  color: var(--light-grey);
}

.app-container p a {
  color: var(--link);
  text-decoration: none;
}

.app-container p a:hover {
  text-decoration: underline;
}

.card-container {
  max-width: 70vw;
  height: 100%;
  display: flex;
  flex-direction: row;
  flex-wrap: wrap;
  justify-content: center;
}

@media screen and (min-width:977px) and (max-width:1024){
  .card-container{
    max-width: 90vw;
  }
}

.card {
  border: 1px solid var(--card-blue);
  box-sizing: border-box;
  box-shadow: 2px 2px 10px var(--card-blue);
  background: var(--card-blue);
  display: flex;
  flex-direction: row;
  justify-content: flex-start;
  align-items: center;
  padding: 20px 20px 10px 20px;
  flex: 1 1 45%;
  min-width: 320px;
  margin: 10px;
  transition: 0.5s;
}

.card:hover {
  transform: translateY(-5px);
  transition: 0.3s;
}

.coin-data {
  display: flex;
  flex-direction: column;
  margin-left: 50px;
  width: 100%;
}

.coin-data p:nth-of-type(2) {
  font-size: 2em;
  margin: 0;
}

.coin-name{
  font-weight: bold;
}

.icon-indicator-and-time{
  display: flex;
  align-items: center;
  justify-content: space-between;
  width: 100%;
}

@media screen and (max-width:600px){
  .icon-indicator-and-time{
    flex-direction: column;
    align-items: flex-start;
}
}

.pos {
  color: var(--positive) !important;
  margin: 0 !important;
}

.time-wrapper {
  display: flex;
  align-items: center;
}

.time-wrapper span,.time{
  font-size: 16px;
  color:gray;
}

.exchanges-where-pulled{
  font-size: 16px;
}

.exchanges-where-pulled p{
  color:gold;
}

.neg {
  color: var(--negative) !important;
}

.card img {
  max-width: 85px;
  padding-left: 20px;
  padding-right: 0;
}

footer {
  height: 75px;
  width: 100%;
  bottom: 0;
  left: 0;
  padding: 25px 0px;
  background: var(--dark-grey);

  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
}

footer ul {
  display: flex;
  flex-direction: row;
  margin: 0;
  padding: 0;
}

footer ul li {
  list-style-type: none;
}

footer ul li a {
  font-size: 1.5em;
}

footer i {
  width: 1em;
  padding: 0px 10px;
  text-align: center;
  color: var(--medium-grey);
}

footer i:hover {
  color: var(--light-grey);
}

footer p {
  color: var(--medium-grey) !important;
  font-family: 'Montserrat', sans-serif;
  margin: 0;
  padding: 0;
}

footer p span {
  color: var(--medium-grey);
}
</style>
