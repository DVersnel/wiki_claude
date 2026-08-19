using System;
using System.Collections.Generic;

namespace ArticleReviewApp.Models;

public partial class HamburgerItem
{
    public string Page { get; set; } = null!;

    public string Name { get; set; } = null!;

    public string LiClass { get; set; } = null!;

    public string AClass { get; set; } = null!;
}
