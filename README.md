<h1>Workforce Management Web Application</h1>

<h2>Project Overview:</h2>
<p>This web application was developed as part of a college project in collaboration with a real-world client, emulating a scenario similar to industry practice. The project aimed to streamline HR processes, track employee headcount, manage cost centers, and facilitate internal position vacancy management. It focuses on providing a functional and practical solution tailored to the needs of HR professionals and managers.</p>

<h2>Key Features:</h2>
<ul>
    <li>Dashboard: Provides an overview of key metrics.</li>
    <li>Cost Center Management: Efficiently manages workforce allocation.</li>
    <li>Role & Employee Management: Creates and manages specific roles within the organization.</li>
    <li>Excel Export: Generates excel spreadsheets with essential HR metrics.</li>
    <li>Data Security: Implements access control, user authentication, password hashing, and SQL injection prevention measures.</li>
</ul>

<h2>User Authentication and Access Control:</h2>
<ul>
    <li>Users can log in through a secure login page.</li>
    <li>Access control restricts access to authorized personnel only.</li>
    <li>Manual user creation is possible in the database.</li>
    <li>Security measures include password hashing, prepared SQL statements, and XSS attack prevention.</li>
</ul>

<h2>Consistent User Interface:</h2>
<p>A cohesive interface is applied across all application sections for a smooth user experience.</p>

<h2>Technologies Used:</h2>
<ul>
    <li>IDE: Visual Studio Code</li>
    <li>Backend: PHP</li>
    <li>Frontend: HTML, CSS, JavaScript</li>
    <li>Database: MySQL</li>
    <li>Server Environment: MAMP/XAMPP</li>
</ul>

<h2>Goals:</h2>
<ul>
    <li>Streamline job position tracking and status monitoring.</li>
    <li>Organize cost center, employee, role, and position data efficiently.</li>
    <li>Enable generation of excel spreadsheets focusing on essential metrics like open roles and attrition.</li>
    <li>Improve data security through access control and user authentication.</li>
</ul>

<h2>Usage:</h2>
<p>This web application is designed for HR professionals and managers, offering CRUD (Create, Read, Update, Delete) functionality.  It features a main dashboard page displaying key metrics related to headcount and attrition, with options for filtering by location and/or cost center. Additionally, it includes sections for Cost Center Management, Employee Management, Role Management, and Position Management, each displaying current data from the corresponding tables in the database. For instance, the Cost Center Management page presents all entries from the "Cost Centers" table. Authorized users with the necessary permissions can add and edit entries via the respective  forms, and in some cases delete entries in these sections. Please note that the demo account provided for public access has restricted permissions, allowing users to view existing data but preventing them from performing create, update, or delete operations.</p>

<h2>Contributing:</h2>
<p>As this project was developed for educational purposes, contributions may not be accepted. However, feel free to fork the repository and explore the codebase.</p>
