<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8">
  <!-- <title>CodePen - Valid email</title> -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">
<style>
	input[type=email]:invalid:not(:focus):not(:placeholder-shown),
input[type=password]:invalid:not(:focus):not(:placeholder-shown) {
  border-bottom: 3px solid #FF5D73;
}
input[type=email]:invalid:not(:focus):not(:placeholder-shown) ~ .form__requirements,
input[type=password]:invalid:not(:focus):not(:placeholder-shown) ~ .form__requirements {
  max-height: 200px;
}
input[type=email]:invalid:focus:not(:placeholder-shown),
input[type=password]:invalid:focus:not(:placeholder-shown) {
  border-bottom: 3px solid #FF5D73;
}

.form {
  max-width: 50%;
  min-width: 400px;
  display: flex;
  padding: 2rem 3rem;
  flex-direction: column;
  background-color: #fff;
    box-shadow: rgba(0, 0, 0, 0.02) 0px 1px 3px 0px, rgba(27, 31, 35, 0.15) 0px 0px 0px 1px;
}
.form fieldset {
  display: flex;
  flex-direction: column;
  min-height: 100px;
}
.form fieldset:last-child {
  min-height: 0;
}
.form__label {
  font-weight: 400;
  letter-spacing: 0.5px;
}
.form__input {
  margin: 0.5rem 0 0.25rem;
  width: 100%;
  padding: 0.825rem 0 0.825rem 1rem;
  background-color: #f5f5f5;
  border: 0;
  border-bottom: 3px solid transparent;
}
.form__button {
  background-color: #ACEDFF;
  border: 0;
  padding: 0.825rem 1rem;
  margin-left: auto;
}
.form__requirements {
  color: #FF5D73;
  font-size: 0.75rem;
  margin: 0.5rem 0;
  overflow: hidden;
  max-height: 0;
}

* {
  transition: 280ms all;
  box-sizing: border-box;
}

html, body {
  height: 100%;
  width: 100%;
}

body {
  display: flex;
  align-items: center;
  justify-content: center;
  flex-direction: column;
    background-image: url('/public/image/background_svg.svg');
  font-family: "open sans";
  -webkit-font-smoothing: antialiased;
}
body span {
  color: #fff;
  margin-bottom: 2rem;
  font-size: 2.5rem;
  font-weight: 600;
}
</style>

</head>
<body>
	
	@if(Session::has('message'))
<p class="alert alert-info">{{ Session::get('message') }}</p>
@endif

<form class="form"  action="{{url('user-quotataion-check')}}" method="post">
	@csrf
  <fieldset>
    <input required type="hidden" class="form__input" name="id" value="{{$id}}">
		<label for="login" class="form__label">Name</label>
		<input required type="text" class="form__input" name="login" placeholder="username" />
	</fieldset>
	<fieldset>
		<label for="email" class="form__label">Email</label>
		<input required type="email" class="form__input" name="email" placeholder="example@aol.com">
	</fieldset>
	<fieldset>
		<button class="form__button" type="submit" name="submit">Submit</button>
	</fieldset>
</form>
<!-- partial -->
  
</body>
</html>
