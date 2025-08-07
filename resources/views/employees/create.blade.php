<!DOCTYPE html>
<html>
<body>

<h2>HTML Forms</h2>

<form method="post" action="{{ route('employee.store') }}">
                        @csrf
  <label for="empID">Employee ID:</label><br>
  <input type="text" id="empID" name="empID" ><br>

  <label for="name">Name - Last Name:</label><br>
  <input type="text" id="name" name="name" ><br><br>

  <label for="ssn">ID Card:</label><br>
  <input type="text" max="13" id="ssn" name="ssn" ><br>

  <label for="tel">tel:</label><br>
  <input type="tel" id="tel" name="tel" ><br><br>

  <label for="gender">gender:</label><br>

  <input type="radio" id="male" name="gender" value="male">
  <label for="male">male</label><br>
  <input type="radio" id="female" name="gender" value="female">
  <label for="female">female</label><br><br>

  <label for="berthdate">berthdate:</label><br>

  <input type="date" max="2007-12-31" id="berthdate" name="berthdate" ><br><br>

  <label for="address">address:</label><br>
  <input type="text" id="address" name="address" ><br>

  <label for="description">Employee description:</label><br>
  <input type="text" id="description" name="description" ><br><br>

  <label for="department">department:</label><br>
  <input type="text" id="department" name="department" ><br>

  <label for="jobdescription">jobdescription:</label><br>
  <input type="text" id="jobdescription" name="jobdescription" ><br><br>

  <label for="manager">manager:</label><br>
  <input type="text" id="manager" name="manager" ><br>

  <label for="createEmployeeBy">createEmployeeBy:</label><br>
  <input type="text" id="createEmployeeBy" name="createEmployeeBy" value="                    {{ Auth::user()->name }}" ><br><br>

  <input type="submit" value="Submit">
</form> 

<p>If you click the "Submit" button, the form-data will be sent to a page called "/action_page.php".</p>

</body>
</html>
