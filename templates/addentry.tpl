<form method="post"
 action="addentry">
<table cellpadding="0" cellspacing="0" border="0" bgcolor="#eeeeee" width="100%"
 style="text-align: left;">
  <tbody>
    <tr>
      <td class="header" style="height: 15px;">
        Title: <input name="title" size=40 value="{$title}">
      </td>
    </tr>
    <tr>
      <td class="source" align=right>-
      </td>
    </tr>
    <tr>
      <td>
        Name:<BR> <input name="username" value="{$username}"><BR>
	Type:<BR> <select name="type">
			<option selected>comment</option>
			<option>code</option>
		  </select><BR>
        Content:<BR>
        <textarea name="content" rows="10" cols="50">{$content}</textarea>
        <BR>
        <input type="submit" name="submit" value="Submit">
        <input type="submit" name="submit" value="Preview">
        <input type="hidden" name="cid"    value="{$cid}">
        <input type="hidden" name="rcid"   value="{$rcid}">
      </td>
    </tr>
  </tbody>
</table>
