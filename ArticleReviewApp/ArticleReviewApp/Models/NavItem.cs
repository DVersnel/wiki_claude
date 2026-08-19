using System;
using System.Collections.Generic;

namespace ArticleReviewApp.Models;

public partial class NavItem
{
    public string Page { get; set; } = null!;

    public string Name { get; set; } = null!;

    public string LiClass { get; set; } = null!;

    public string AClass { get; set; } = null!;

    public bool LoggedIn { get; set; }
}
