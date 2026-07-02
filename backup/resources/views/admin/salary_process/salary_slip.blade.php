<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
   <!--   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" /> -->
    <style>
        table {
            font-family: sans-serif;

        }

        table {
            border-collapse: collapse;
        }

        table tr td {
            padding: 7.2px;
        }

        table tr th {

            text-align: start;
            padding: 5px;
            font-family: sans-serif;
        }
    </style>
</head>

<body>
    <section style="width: 100%; text-align: center;">

        <table style="width:100%; text-align: start;">
            <tr>
                <td style="border:1px solid #fff; width:40%; text-align:start;padding: 0px;">
                    <h2 style="margin: 0px;">Kshitij Vivan</h2>
                </td>
                <td rowspan="7" style="width: 40%; border:1px solid #fff; text-align:end;padding: 0px;">
                    <img style="text-align:start;float: right;" width="150" src="https://www.hrms.kshitijvivan.com/assets/frontassets/images/final kv logo.png" alt="">
                </td>
            </tr>
            <tr>
                <td style="border:1px solid #fff; width:40%; text-align:start;padding: 0px;">
                    <p style="margin: 0px;">116,First Floor,Shivalik Ship,Iskcon Cross Road</p>
                </td>
            </tr>
            <tr>
                <td style="border:1px solid #fff; width:40%; text-align:start;padding: 0px;">
                    <p style="margin: 0px;">S.G Highway,Ahmedabad - 380015</p>
                </td>
            </tr>
            <tr>
                <td style="border:1px solid #fff; width:40%; text-align:start;padding: 0px;">
                    ph: +91 9137403463 | ashish@kshitijvivan.com
                </td>
            </tr>

        </table>
        <table style="width:100%; text-align: start;">
            <tr>
                <td style="border-bottom: 2px solid #f4cc21;"></td>
            </tr>
        </table>
        <br>
        <br>

        <table style="width: 100%;">
            <tr>
                <!-- <td
                    style="text-align: center; font-weight: 600;font-size:24px; padding-top: 10px;text-transform: uppercase;background-color: #f4cc21;color: white;">
                    Salary Slip</td> -->
                <th  colspan="2" >Salary Slips of {{ $salary->employeeName }} </th>

            </tr>
        </table>

        <table style="width: 100%; padding-bottom: 10px;">
            <tr>
                <?php   $monthYear = date('M Y', strtotime("{$salary->salary_year}-{$salary->salary_month}-01"));
 ?>

                <td style="border:1px solid #f4cc21;"> </td>
                <td style="border:1px solid #f4cc21;"> {{$monthYear }}'</td>
            </tr>

            <tr>

                <td style="border:1px solid #f4cc21;"> </td>
                <td style="border:1px solid #f4cc21;"> </td>
            </tr>

            <tr>

                <td style="border:1px solid #f4cc21;">Basic Salary </td>
                <td style="border:1px solid #f4cc21;">{{ $salary->basic_salary }} </td>
            </tr>  
            <tr>
                <td style="border:1px solid #f4cc21;">Incentive </td>
                <td style="border:1px solid #f4cc21;">{{ $salary->Incentive }} </td>
            </tr>
            <tr>
                <td style="border:1px solid #f4cc21;">Bonus </td>
                <td style="border:1px solid #f4cc21;">{{ $salary->Bonus }} </td>
            </tr> 
            <tr>
                <td style="border:1px solid #f4cc21;">Others </td>
                <td style="border:1px solid #f4cc21;">{{ $salary->Others }} </td>
            </tr>

            <tr>

                <td style="border:1px solid #f4cc21;">Total - A </td>
                <td style="border:1px solid #f4cc21;"> {{ $salary->Total_A }}</td>
            </tr>
            <tr>

                <td style="border:1px solid #f4cc21;"></td>
                <td style="border:1px solid #f4cc21;"></td>
            </tr>

            <tr>

                <td style="border:1px solid #f4cc21;">WDIM</td>
                <td style="border:1px solid #f4cc21;"> {{ $salary->WDIM }}</td>
            </tr>

            <tr>

                <td style="border:1px solid #f4cc21;">HDIM</td>
                <td style="border:1px solid #f4cc21;"> {{ $salary->HDIM }}</td>
            </tr>

            <tr>

                <td style="border:1px solid #f4cc21;">Leave Ded.</td>
                <td style="border:1px solid #f4cc21;"> {{ $salary->Leave_ded }}</td>
            </tr>

            <tr>
                <td style="border:1px solid #f4cc21;">PT</td>
                <td style="border:1px solid #f4cc21;"> {{ $salary->PT }}</td>
            </tr>
            <tr>

                <td style="border:1px solid #f4cc21;">TDS</td>
                <td style="border:1px solid #f4cc21;"> {{ $salary->TDS }}</td>
            </tr>
            <tr>

                <td style="border:1px solid #f4cc21;">Loan /Advance</td>
                <td style="border:1px solid #f4cc21;"> {{ $salary->Loan_Advance }}</td>
            </tr>
            <tr>
                <td style="border:1px solid #f4cc21;">Total B:</td>
                <td style="border:1px solid #f4cc21;">{{ $salary->Total_B }}</td>
            </tr>
            <tr>

                <td style="border:1px solid #f4cc21;" ></td>
                <td style="border:1px solid #f4cc21;"></td>
            </tr>
            <tr>

                <td style="border:1px solid #f4cc21;">Net Pay (A-B)</td>
                <td style="border:1px solid #f4cc21;"> {{ $salary->net_pay }}</td>
            </tr>
        </table>
        <br>
        <br>
        <br>

        <table style="width: 100%; padding-bottom: 10px;">
            <tr>
                <td style="text-align: start;">Signature</td>

            </tr>
        </table>

    </section>
    <br>
    <br>
    <br>


</body>

</html>