namespace ArticleReviewApp.Models;

/// <summary>
/// Bit flags used for both <see cref="User.Roles"/> (roles a user holds) and
/// <see cref="Article.AccessMask"/> (roles allowed to access the article).
/// A user can access an article when the two masks share at least one bit:
/// (article.AccessMask &amp; user.Roles) != 0.
/// Values are stored as INT in the database, so never renumber existing flags.
/// </summary>
[Flags]
public enum Role
{
    None     = 0,
    Reader   = 1 << 0, // 1
    Reviewer = 1 << 1, // 2
    Editor   = 1 << 2, // 4
    Admin    = 1 << 3, // 8

    All = Reader | Reviewer | Editor | Admin
}
