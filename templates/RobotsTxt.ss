<% cached 'RobotsTxt', $Subsite.ID, $isLive, $DisallowedFolderList.max(LastEdited), $DisallowedFolderList.count() %><%--
--%># robots.txt for $Subsite.Title

User-agent: * <%--
--%><% if $isLive %><%--
    --%><% if $DisallowedFolderList %>  <%--
        --%><% loop $DisallowedFolderList %>
Disallow: $Filename<%--
        --%><% end_loop %><%--
    --%><% else %>
Disallow: <%--
    --%><% end_if %><%--
--%><% else %>
Disallow: /<%--
--%><% end_if %>
<% end_cached %>
