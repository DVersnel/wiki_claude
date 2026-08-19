using System;
using System.Collections.Generic;

namespace ArticleReviewApp.Models;

public partial class FormField
{
    public int PageId { get; set; }

    public string Label { get; set; } = null!;

    public string Type { get; set; } = null!;

    public string Name { get; set; } = null!;

    public string Placeholder { get; set; } = null!;

    public int? SortOrder { get; set; }
}
