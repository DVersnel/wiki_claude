using System;
using System.Collections.Generic;

namespace ArticleReviewApp.Models;

public partial class PageTitleDescription
{
    public string Page { get; set; } = null!;

    public string PageTitle { get; set; } = null!;

    public string PageDescription { get; set; } = null!;
}
