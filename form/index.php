<!DOCTYPE html>
<html>
    <head>
        <title>Confidentiality Agreement</title>
        <style>
            /* CSS styles */
            body {
                width: 90%;
                margin: 0 auto;
                font-family: Arial, sans-serif;
                background-color: #f5f5f5;
            }
            label {
                font-weight: bold;
            }
            input[type="text"],
            input[type="email"] {
                width: 100%;
                padding: 10px;
                margin-bottom: 20px;
                border: 1px solid #ccc;
                border-radius: 4px;
                box-sizing: border-box;
            }
            input[type="submit"] {
                background-color: #4CAF50;
                color: white;
                padding: 10px 20px;
                border: none;
                border-radius: 4px;
                cursor: pointer;
            }
            input[type="submit"]:hover {
                background-color: #45a049;
            }
        </style>
    </head>
    <body>
        <h1>Confidentiality Agreement</h1>
        <form action="agree.php" method="post">
            <p>Please read and agree to the following terms:</p>
            <p><strong>Confidentiality Agreement for Skybyn Project</strong></p>
            <p>This Confidentiality Agreement (the "Agreement") is entered into on the date of signing by the undersigned (the "Recipient"), in connection with the Skybyn project (the "Project").</p>
            <ol>
                <li><strong>Confidential Information:</strong> The Recipient agrees to keep confidential all information and materials related to the Project including but not limited to technical data, trade secrets, business plans, and financial information (the "Confidential Information"). Access to all documents and codes related to the Project will be granted only after signing this Agreement.</li>
                <li><strong>Use and Disclosure:</strong> The Recipient agrees that the Confidential Information shall only be used for the purposes of the Project and shall not be disclosed to any third party without the express written consent of the current project leader.</li>
                <li><strong>Violation of Agreement:</strong> The Recipient acknowledges that any unauthorized use or disclosure of the Confidential Information will cause irreparable harm to the Project, and the Recipient agrees that in the event of a breach of this Agreement, the Project shall have the right to seek injunctive relief, as well as any other legal remedies available.</li>
                <li><strong>Consequences of Violation:</strong> The Recipient understands that any violation of this Agreement will result in the immediate termination of their access to the Project and service data, including accounts.</li>
                <li><strong>Duration of Agreement:</strong> The obligations of this Agreement shall remain in effect at all times, and the Recipient shall continue to be bound by the terms of this Agreement even after their access to the Project has ended.</li>
                <li><strong>Non-Circumvention:</strong> The Recipient agrees not to use the Confidential Information for any purpose other than those specified in this Agreement, and agrees not to circumvent the intent of this Agreement by engaging in any activities or practices that would result in the unauthorized use or disclosure of the Confidential Information.</li>
                <li><strong>Acknowledgment of Agreement:</strong> The Recipient acknowledges that they have read and understood this Agreement, and that they agree to be bound by its terms.</li>
                <li><strong>Governing Law:</strong> This Agreement shall be governed by and construed in accordance with the laws of the jurisdiction in which the Project is based.</li>
                <li><strong>Entire Agreement:</strong> This Agreement constitutes the entire agreement between the parties with respect to the subject matter hereof and supersedes all prior negotiations, understandings and agreements between the parties relating to such subject matter.</li>
                <li><strong>Amendment and Waiver:</strong> This Agreement may be amended or modified only by a written instrument executed by both parties. No waiver of any provision of this Agreement shall be valid unless in writing and signed by the party against whom enforcement of such waiver is sought.</li>
            </ol>
            <p>
                <label for="name">Name:</label>
                <input type="text" name="name" required>
            </p>
            <p>
                <label for="email">Email:</label>
                <input type="email" name="email" required>
            </p>
            <p>
                <input type="submit" value="Agree">
            </p>
        </form>
    </body>
</html>