<h3>Donors with first donation >= 10.000 added to Platinum</h3>

<p>{$endText}</p>
<h3>Contacts added: </h3>
<table>
    <tr>
        <th>Id</th>
        <th>Name</th>
        <th>Contribution Id</th>
        <th>Amount</th>
        <th>Date</th>
    </tr>
    {foreach from=$addedContacts item=addedContact}
        <tr>
            <td>{$addedContact.contact_id}</td>
            <td>{$addedContact.display_name}</td>
            <td>{$addedContact.contribution_id}</td>
            <td>{$addedContact.total_amount}</td>
            <td>{$addedContact.receive_date}</td>
        </tr>
    {/foreach}
</table>
