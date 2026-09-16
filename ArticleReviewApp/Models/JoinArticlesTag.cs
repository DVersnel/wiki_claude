using System;
using System.Collections.Generic;

namespace ArticleReviewApp.Models;

public partial class JoinArticlesTag
{
    public int ArticleId { get; set; }

    public int TagId { get; set; }

    public virtual Article Article { get; set; } = null!;

    public virtual Tag Tag { get; set; } = null!;
}
